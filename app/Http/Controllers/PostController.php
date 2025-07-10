<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest\StorePostRequest;
use App\Http\Requests\PostRequest\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
        // Áp dụng policy middleware
        $this->middleware('can:update,post')->only(['edit', 'update']);
        $this->middleware('can:delete,post')->only(['destroy']);
    }

    // public function index()
    // {
    //    // Lấy toàn bộ bài viết đã đăng
    //     $posts = Post::where('user_id', Auth::id())
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     return view('posts.index', compact('posts'));
    // }
    public function index()
    {
        return view('posts.index');
    }

    public function getData(Request $request)
    {
        return response()->json($this->postService->getDatatablePosts($request));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePostRequest $request)
    {
        try {
            $this->postService->store($request->validated(), $request);
            return to_route('posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi tạo bài viết: ' . $e->getMessage());
        }
    }

    public function edit(Post $post)
    {
        //$this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        //$this->authorize('update', $post);

        try {
            $this->postService->update($post, $request->validated(), $request);
            return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật bài viết: ' . $e->getMessage());
        }
    }

    public function destroy(Post $post)
    {
        //$this->authorize('delete', $post);

        $post->delete();
        return to_route('posts.index')->with('success', 'Xóa bài viết thành công');
    }

    public function destroyAll()
    {
        Post::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Đã xoá tất cả bài viết']);
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
