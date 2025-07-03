<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Enums\PostStatus;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', PostStatus::PUBLISHED)
                    ->orderByDesc('publish_date')
                    ->paginate(6);

        return view('news.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->status === PostStatus::PUBLISHED, 404);

        return view('news.show', compact('post'));
    }
}
