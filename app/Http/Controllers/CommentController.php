<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request, Post $post)
    {
        /** @var \Illuminate\Http\Request $request */
        $comment = $this->commentService->createComment(
            $post,
            $request->input('body'),     
            $request->ip()              
        );

        return response()->json([
            'comment' => [
                'user' => $comment->user->name ?? 'Khách',
                'body' => $comment->body
            ]
        ]);
    }

}
