<?php

namespace App\Services;

use App\Models\Post;
use App\Enums\PostStatus;
use App\Enums\RoleStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;

class PostService
{
    public function store(array $validated, Request $request): Post
    {
        DB::beginTransaction();

        try {
            $validated['user_id'] = Auth::id();

            // Nếu là admin thì được phép chọn status
            if (Auth::check() && Auth::user()?->role === RoleStatus::ADMIN->value) {
                $validated['status'] = PostStatus::from(
                    $validated['status'] ?? PostStatus::NEW->value
                );
            } else {
                // Không phải admin thì luôn là NEW
                $validated['status'] = PostStatus::NEW;
            }

            $validated['publish_date'] = $request->filled('publish_date')
                ? now()->parse($request->input('publish_date'))
                : null;

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

    public function update(Post $post, array $validated, Request $request): Post
    {
        DB::beginTransaction();

        try {
            $updateData = $validated;

            $updateData['publish_date'] = $request->filled('publish_date')
                ? now()->parse($request->input('publish_date'))
                : null;

            // Nếu là admin mới xử lý status
            if (Auth::check() && Auth::user()?->role === RoleStatus::ADMIN->value) {
                $updateData['status'] = PostStatus::from(
                    $validated['status'] ?? $post->status->value
                );
            } else {
                unset($updateData['status']);
            }

            // Cập nhật bài viết
            $post->update($updateData);

            // Nếu có thumbnail mới, xoá cái cũ và gán cái mới
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

}
