<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Services\LikeService;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likePost(Post $post, Request $request)
    {
        $count = $this->likeService->toggleLike($post, $request);
        return response()->json(['count' => $count]);
    }

    public function likeComment(Comment $comment, Request $request)
    {
        $count = $this->likeService->toggleLike($comment, $request);
        return response()->json(['count' => $count]);
    }
}
