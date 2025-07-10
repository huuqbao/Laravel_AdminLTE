<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Support\Carbon;

class NewsController extends Controller
{
    public function index()
    {
        $today = Carbon::now();

        $posts = Post::where('status', PostStatus::PUBLISHED)
                    ->where('publish_date', '<=', $today)
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
