<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\NewsService;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $posts = $this->newsService->getPublishedPostsPaginated();

        if ($request->ajax()) {
            return view('news.partials', compact('posts'))->render();
        }

        return view('news.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($this->newsService->isPostVisible($post), 404);

        $post->loadCount('likes');

        // Nạp toàn bộ quan hệ bình luận nhiều cấp
        $post->load([
            'comments' => function ($q) {
                $q->whereNull('parent_id')->latest();
            },
            'comments.user',
            'comments.likes',
            'comments.replies.user',
            'comments.replies.likes',
            'comments.replies.replies.user',
            'comments.replies.replies.likes',
        ]);

        $userLiked = $post->likes()
            ->when(Auth::check(), fn($q) => $q->where('user_id', Auth::id()))
            ->when(!Auth::check(), fn($q) => $q->where('ip_address', request()->ip()))
            ->exists();

        return view('news.show', [
            'post' => $post,
            'userLiked' => $userLiked,
            'comments' => $post->comments, 
        ]);
    }

}
