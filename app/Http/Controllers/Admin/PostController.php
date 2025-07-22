<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\PostStatus;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\Admin\PostService;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function __construct(protected PostService $adminpostService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $filters = [
                'custom_search' => $request->input('custom_search'),
                'status' => $request->input('status'),
                'start' => (int) $request->input('start', 0),
                'length' => (int) $request->input('length', 10),
                'draw' => (int) $request->input('draw'),
                'order' => $request->input('order.0', ['column' => 0, 'dir' => 'desc']),
            ];

            return response()->json($this->adminpostService->getDatatablePosts($filters));
        }

        return view('admin.posts.index');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        try {
            $this->adminpostService->update($post, $validated);

            /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('thumbnail')) {
                $post->clearMediaCollection('thumbnail');
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return to_route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Cập nhật bài viết thất bại');
        }
    }

    public function destroy(Post $post, Request $request)
    {
        $this->adminpostService->deletePost($post);
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Xóa bài viết thành công.']);
        }

        return to_route('admin.posts.index')->with('success', 'Xóa bài viết thành công');
    }

    public function destroyAll()
    {
        $deleted = $this->adminpostService->deleteAll();
        return response()->json(['message' => 'Đã xoá ' . $deleted . ' bài viết']);
    }

    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->adminpostService->store($request->validated());

            /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('thumbnail')) {
                $post->addMediaFromRequest('thumbnail')->toMediaCollection('thumbnail');
            }

            return to_route('admin.posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors('Lỗi khi tạo bài viết: ' . $e->getMessage());
        }
    }


    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }
}
