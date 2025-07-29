<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function createComment(Post $post, string $body, string $ipAddress, ?int $parentId = null): Comment
    {
        $data = [
            'body' => $body,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'ip_address' => $ipAddress,
            'parent_id' => $parentId ?: null,
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        return Comment::create($data)->fresh([
            'user',
            'likes',
            'replies.user',
            'replies.likes',
            'replies.replies.user',
            'replies.replies.likes',
        ]);
    }
}
