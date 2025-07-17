<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Enums\RoleStatus;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class AdminPostService
{
    public function getDatatablePosts(): array
    {
        $request = request();

        $query = Post::query()->with('user');

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', fn ($q) => $q->where('email', 'like', "%$search%"));
            });
        }

        $total = $query->count();

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);

        $columns = ['id', 'thumbnail', 'title', 'description', 'publish_date', 'status'];
        $order = $request->input('order.0', ['column' => 0, 'dir' => 'desc']);
        $sortColumn = $columns[(int) ($order['column'] ?? 0)] ?? 'id';
        $sortDir = $order['dir'] === 'asc' ? 'asc' : 'desc';

        $posts = $query->orderBy($sortColumn, $sortDir)
            ->skip($start)
            ->take($length)
            ->get();

        $data = $posts->map(function ($post, $index) use ($start) {
            return [
                'DT_RowIndex' => $start + $index + 1,
                'thumbnail' => $post->thumbnail,
                'title' => Str::limit($post->title, 50),
                'description' => Str::limit($post->description, 80),
                'publish_date' => $post->publish_date,
                'status' => $post->status_badge,
                'id' => $post->id,
                'user_id' => $post->user?->id,
            ];
        });


        return [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
            'totalPosts' => Post::count(), 
        ];
    }

    public function update(Post $post, array $validated): Post
    {
        $request = request();

        DB::beginTransaction();

        try {
            $updateData = $validated;
            $updateData['content'] = str_replace(['<p>', '</p>'], '', $request->input('content'));
            $updateData['publish_date'] = $request->filled('publish_date')
                ? now()->parse($request->input('publish_date'))
                : null;

            // Lưu trạng thái cũ để so sánh
            $oldStatus = $post->status;

            if (Auth::user()?->role === RoleStatus::ADMIN) {
                // Kiểm tra xem 'status' có được gửi lên từ form không
                if (isset($validated['status'])) {
                    // Chuyển giá trị số (0, 1, 2) thành đối tượng Enum
                    $updateData['status'] = PostStatus::from((int)$validated['status']);
                }
            } else {
                // Nếu không phải admin, loại bỏ 'status' khỏi mảng cập nhật để đảm bảo an toàn
                unset($updateData['status']);
            }


            // Nếu đổi tiêu đề, xóa slug cũ
            if ($updateData['title'] !== $post->title) {
                $updateData['slug'] = null;
            }

            $post->update($updateData);

            // Cập nhật ảnh nếu có
            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnail');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            // Nếu status thay đổi, gửi email
            if (isset($updateData['status']) && $updateData['status'] !== $oldStatus) {
                dispatch(new \App\Jobs\SendPostStatusEmail($post));
            }

            DB::commit();
            return $post;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Post update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    public function deleteAll(): int
    {
        return Post::query()->delete();
    }

    public function store(array $validated): Post
    {
        $request = request();
        
        DB::beginTransaction();

        try {
            $validated['user_id'] = Auth::id();

            if (Auth::check() && Auth::user()?->role === RoleStatus::ADMIN->value) {
                $validated['status'] = PostStatus::from(
                    $validated['status'] ?? PostStatus::NEW->value
                );
            } else {
                $validated['status'] = PostStatus::NEW;
            }

            $validated['publish_date'] = $request->filled('publish_date')
                ? now()->parse($request->input('publish_date'))
                : null;
            
            $validated['content'] = str_replace(['<p>', '</p>'], '', $request->input('content'));  
                
            $post = Post::create($validated);

            if ($request->hasFile('thumbnail')) {
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            DB::commit();
            return $post;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
