<?php

namespace App\Services;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PostService
{
    public function store(array $validated, Request $request): Post
    {
        return DB::transaction(function () use ($validated, $request) {
            $validated['user_id'] = Auth::id();

            $validated['status'] = (int) ($validated['status'] ?? PostStatus::NEW->value);

            $validated['publish_date'] = $request->filled('publish_date')
                ? now()->parse($request->input('publish_date'))
                : null;

            $post = Post::create($validated);

            if ($request->hasFile('thumbnail')) {
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return $post;
        });
    }

    public function update(Post $post, array $validated, Request $request): Post
    {
        return DB::transaction(function () use ($post, $validated, $request) {
            $updateData = $validated;

            $updateData['publish_date'] = $request->filled('publish_date')
                ? now()->parse($request->input('publish_date'))
                : null;

            if (
                Auth::user()?->is_admin ||
                $post->user_id === Auth::id()
            ) {
                $updateData['status'] = $validated['status'] ?? $post->status->value;
            } else {
                unset($updateData['status']);
            }

            $post->update($updateData);

            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnail');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return $post;
        });
    }
}
