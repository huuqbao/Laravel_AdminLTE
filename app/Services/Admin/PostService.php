<?php

namespace App\Services\Admin;

use App\Enums\PostStatus;
use App\Enums\RoleStatus;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;
use App\Jobs\SendPostStatusEmail;
use App\Http\Resources\Admin\PostResource;

class PostService
{
    public function getDatatablePosts(array $filters): array
    {
        $query = Post::query()->with('user');

        // Custom search theo title + description
        $search = $filters['custom_search'] ?? null;
        if (!empty($search)) {
            $query->whereAny([
                ['title', 'like', "%{$search}%"],
                ['description', 'like', "%{$search}%"],
            ]);
        }

        // Lọc theo status nếu có
        $status = $filters['status'] ?? null;
        if ($status !== null && $status !== '') {
            $query->where('status', (int)$status);
        }

        $total = $query->count();

        $start = (int) ($filters['start'] ?? 0);
        $length = (int) ($filters['length'] ?? 10);

        $columns = ['id', 'thumbnail', 'title', 'description', 'publish_date', 'status'];
        $order = $filters['order'][0] ?? ['column' => 0, 'dir' => 'desc'];
        $sortColumn = $columns[(int) ($order['column'] ?? 0)] ?? 'id';
        $sortDir = strtolower($order['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $posts = $query->orderBy($sortColumn, $sortDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Gán index
        $posts->each(function ($post, $index) use ($start) {
            $post->index = $start + $index + 1;
        });

        return [
            'draw' => (int) ($filters['draw'] ?? 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => PostResource::collection($posts)->toArray(request()),
            'totalPosts' => Post::count(),
        ];
    }


    public function update(Post $post, array $validated): Post
    {
        DB::beginTransaction();

        try {
            $updateData = $validated;

            $updateData['publish_date'] = isset($validated['publish_date'])
                ? now()->parse($validated['publish_date'])
                : now();

            $updateData['content'] = $validated['content'] ?? $post->content;

            $oldStatus = $post->status;

            if (Auth::user()?->role === RoleStatus::ADMIN) {
                if (isset($validated['status'])) {
                    $updateData['status'] = PostStatus::from((int) $validated['status']);
                }
            } else {
                unset($updateData['status']);
            }

            if ($updateData['title'] !== $post->title) {
                $updateData['slug'] = null;
            }

            $post->update($updateData);

            if (isset($updateData['status']) && $updateData['status'] !== $oldStatus) {
                dispatch(new SendPostStatusEmail($post));
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
        DB::beginTransaction();

        try {
            $validated['user_id'] = Auth::id();

            // Gán status tùy role
            if (Auth::check() && Auth::user()?->role === RoleStatus::ADMIN->value) {
                $validated['status'] = PostStatus::from(
                    $validated['status'] ?? PostStatus::NEW->value
                );
            } else {
                $validated['status'] = PostStatus::NEW;
            }

            // Xử lý publish_date
            $validated['publish_date'] = isset($validated['publish_date'])
                ? now()->parse($validated['publish_date'])
                : now();

            // content là required trong request, nên đã validated
            // Không cần lấy từ $request->input('content')
            $post = Post::create($validated);

            DB::commit();
            return $post;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Post creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

}
