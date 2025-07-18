<?php

namespace App\Services;

use App\Models\Post;
use App\Enums\PostStatus;
use App\Enums\RoleStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
    
class PostService
{
    public function store(array $validated): Post
    {
        $request = request();

        DB::beginTransaction();

        try {
            $validated['user_id'] = Auth::id();

            $validated['status'] = PostStatus::NEW;

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

            unset($updateData['status']);

            $post->update($updateData);

            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnail');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            DB::commit();
            return $post;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Post update failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getDatatablePosts(Request $request): array
    {
        $query = Post::query()->where('user_id', Auth::id());

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }
        $total = $query->count();

        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 3);

        $columns = ['id', 'thumbnail', 'title', 'description', 'publish_date', 'status']; 
        $order = $request->input('order.0', ['column' => 0, 'dir' => 'desc']);
        $columnIndex = (int) ($order['column'] ?? 0);
        $sortColumn = $columns[$columnIndex] ?? 'id';
        $sortDir = $order['dir'] === 'asc' ? 'asc' : 'desc';


        $posts = $query->orderBy($sortColumn, $sortDir)
            ->paginate($length, ['*'], 'page', intval($start / $length) + 1);

        $data = $posts->map(function ($post, $index) use ($start) {
            return [
                'DT_RowIndex' => $start + $index + 1,
                'thumbnail' => $post->thumbnail,
                'title' => Str::limit($post->title, 50),
                'description' => Str::limit($post->description, 80),
                'publish_date' => $post->publish_date,
                'status' => $post->status_badge,
                'id' => $post->id
            ];
        })->toArray();

        return [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => Post::where('user_id', Auth::id())->count(),
            'recordsFiltered' => $total,
            'data' => $data,
        ];
    }

    public function destroy(Post $post): void
    {
        $post->delete();
    }

    public function destroyAllByUser(): void
    {
        Auth::user()->posts()->delete();
    }

}
