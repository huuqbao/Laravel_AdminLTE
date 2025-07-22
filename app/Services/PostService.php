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
use App\Http\Resources\PostResource;
    
class PostService
{
    public function store(array $validated): Post
    {
        DB::beginTransaction();

        try {
            $validated['user_id'] = Auth::id();
            $validated['status'] = PostStatus::NEW;
            $validated['publish_date'] = now()->parse($validated['publish_date']);

            $post = Post::create($validated);

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
        DB::beginTransaction();

        try {
            $updateData = $validated;

            $updateData['content'] = request()->input('content');
            $updateData['publish_date'] = request()->filled('publish_date')
                ? now()->parse(request()->input('publish_date'))
                : now();

            unset($updateData['status']);

            $post->update($updateData);

            DB::commit();
            return $post;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Post update failed: ' . $e->getMessage());
            throw $e;
        }
    }


    public function getDatatablePosts(array $filters): array
    {
        $query = Post::query()->where('user_id', Auth::id());

        // Tìm kiếm
        if (!empty($filters['custom_search'])) {
            $search = $filters['custom_search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Lọc theo status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (int) $filters['status']);
        }

        $total = $query->count();

        $start = $filters['start'] ?? 0;
        $length = $filters['length'] ?? 3;

        $columns = ['id', 'thumbnail', 'title', 'description', 'publish_date', 'status'];
        $order = $filters['order'] ?? ['column' => 0, 'dir' => 'desc'];
        $columnIndex = (int) ($order['column'] ?? 0);
        $sortColumn = $columns[$columnIndex] ?? 'id';
        $sortDir = $order['dir'] === 'asc' ? 'asc' : 'desc';

        $posts = $query->orderBy($sortColumn, $sortDir)
            ->paginate($length, ['*'], 'page', intval($start / $length) + 1);

        $posts->each(function ($post, $index) use ($start) {
            $post->index = $index + 1;
        });

        return [
            'draw' => $filters['draw'] ?? 1,
            'recordsTotal' => Post::where('user_id', Auth::id())->count(),
            'recordsFiltered' => $total,
            'data' => PostResource::collection($posts)->toArray(request()),
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
