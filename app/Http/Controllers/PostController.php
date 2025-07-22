<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = [
                'custom_search' => $request->input('custom_search'),
                'status' => $request->input('status'),
                'start' => (int) $request->input('start', 0),
                'length' => (int) $request->input('length', 3),
                'draw' => (int) $request->input('draw'),
                'order' => $request->input('order.0', ['column' => 0, 'dir' => 'desc']),
            ];

            return response()->json($this->postService->getDatatablePosts($filters));
        }

        return view('posts.index');
    }

    public function create()
    {
        return view('posts.create');
    }
    
    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->postService->store($request->validated());

           /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('thumbnail')) {
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return to_route('posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi tạo bài viết: ' . $e->getMessage());
        }
    }


    public function edit(Post $post)
    {
        $post->load('user'); 
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            $this->postService->update($post, $request->validated());

            /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnail');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return to_route('posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Đã xảy ra lỗi khi cập nhật bài viết: ' . $e->getMessage());
        }
    }


    public function destroy(Post $post, Request $request)
    {
        try {
            $this->postService->destroy($post);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Xóa bài viết thành công']);
            }

            return to_route('posts.index')->with('success', 'Xóa bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Lỗi khi xóa bài viết: ' . $e->getMessage());
        }
    }

    public function destroyAll()
    {
        try {
            $this->postService->destroyAllByUser();
            return response()->json(['message' => 'Đã xoá tất cả bài viết']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Xóa thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function show(Post $post)
    {
        $post->load('user');

        return view('posts.show', compact('post'));
    }

}
