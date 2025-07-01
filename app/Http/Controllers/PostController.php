<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10); // bạn đang dùng paginate
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        $validated = $request->validated();

        // Tạo slug duy nhất từ title
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $originalSlug = $slug;
        $i = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        $post = new Post();
        $post->fill($validated);
        $post->user_id = Auth::id(); // phải có
        $post->status = 0;
        $post->slug = $slug;
        $post->save(); // ⚠️ phải có

        // Upload thumbnail (nếu có)
        if ($request->hasFile('thumbnail')) {
            $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnails');
        }
        // dd($post->getMedia('thumbnail')->pluck('original_url'));
        return to_route('posts.index')->with('success', 'Tạo bài viết thành công.');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post); // thêm dòng này
        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
        }

        $post->update($validated);

        return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Xoá bài viết thành công.');
    }

    public function destroyAll()
    {
        Auth::user()->posts()->delete();

        return back()->with('success', 'Xoá tất cả bài viết thành công.');
    }

    public function checkSlug(Request $request)
    {
        $slug = $request->get('slug');
        $exists = Post::where('slug', $slug)->exists();
        return response()->json(['exists' => $exists]);
    }
}
