<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::now();

        $posts = Post::where('publish_date', '<=', $today)
                    ->orderBy('publish_date', 'desc')
                    ->paginate(1);

        if ($request->ajax()) {
            return view('posts.partials._table', compact('posts'))->render();
        }

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = Post::createUniqueSlug($validated['title']);

        $post = new Post($validated);
        $post->user_id = Auth::id();
        $post->status = Auth::user()?->is_admin
            ? PostStatus::tryFrom($validated['status'] ?? PostStatus::DRAFT->value)
            : PostStatus::DRAFT;
        $post->publish_date = request()->filled('publish_date')
            ? \Carbon\Carbon::parse(request()->input('publish_date'))
            : null;
        $post->save();

        if ($request->hasFile('thumbnail')) {
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnail');
        }

        return to_route('posts.index')->with('success', 'Tạo bài viết thành công');
    }


    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Xóa bài viết thành công');
    }

    public function destroyAll()
    {
        Post::query()->delete(); // hoặc Post::truncate();
        return response()->json(['message' => 'Đã xoá tất cả bài viết']);
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $validated = $request->validated();

        if (Auth::user()?->is_admin) {
            $post->status = PostStatus::tryFrom($validated['status'] ?? $post->status->value) ?? $post->status;
        }

        $post->fill($validated);

        // cập nhật publish_date nếu có
        $post->publish_date = request()->filled('publish_date')
                    ? \Carbon\Carbon::parse(request()->input('publish_date'))
                    : null;

        $post->status = $validated['status'] ?? $post->status;

        $post->save();

        // thumbnail mới?
        if ($request->hasFile('thumbnail')) {
            $post->clearMediaCollection('thumbnail');
            $post->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnail');
        }

        return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công');
    }

}
