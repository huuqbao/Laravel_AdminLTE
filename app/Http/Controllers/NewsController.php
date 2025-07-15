<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $request = request();
        $today = now();
        $posts = Post::where('status', PostStatus::PUBLISHED)
                    ->where('publish_date', '<=', $today)
                    ->orderByDesc('publish_date')
                    ->paginate(3);

        if ($request->ajax()) {
            return view('news.partials', compact('posts'))->render();
        }

        return view('news.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->status === PostStatus::PUBLISHED, 404);

        return view('news.show', compact('post'));
    }
}
