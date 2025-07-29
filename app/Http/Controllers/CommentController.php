<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request, Post $post)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Bạn cần đăng nhập để bình luận.'
            ], 403);
        }

        /** @var \Illuminate\Http\Request $request */
        $comment = $this->commentService->createComment(
            $post,
            $request->input('body'),
            $request->ip(),
            $request->input('parent_id')
        );

       

        $html = view('news.comment', ['comment' => $comment])->render();

        return response()->json([
            'html' => $html,
            'parent_id' => $request->input('parent_id')
        ]);
    }



}
