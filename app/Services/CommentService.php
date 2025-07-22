<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function createComment(Post $post, string $body, string $ipAddress): Comment
    {
        $data = [
            'body' => $body,
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'ip_address' => $ipAddress,
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        return Comment::create($data);
    }
}
