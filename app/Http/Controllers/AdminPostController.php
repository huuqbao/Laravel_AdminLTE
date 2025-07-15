<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Http\Requests\PostRequest\StorePostRequest;
use App\Http\Requests\PostRequest\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Services\AdminPostService;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public function __construct(protected AdminPostService $adminpostService) {}

    public function index()
    {
        return view('admin.posts.index');
    }

    public function getData(Request $request)
    {
        return response()->json($this->adminpostService->getDatatablePosts($request));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();

        try {
            $this->adminpostService->update($post, $validated, $request);
            return to_route('admin.posts.index')->with('success', 'Cập nhật bài viết thành công');
        } catch (\Throwable $e) {
            return back()->with('error', 'Cập nhật bài viết thất bại');
        }
    }


    public function destroy(Post $post)
    {
        $this->adminpostService->deletePost($post);
        return response()->json(['success' => true]);
    }

    public function destroyAll()
    {
        $deleted = $this->adminpostService->deleteAll();
        return response()->json(['message' => "Đã xoá $deleted bài viết"]);
    }

    public function store(StorePostRequest $request)
    {
        try {
            $post = $this->adminpostService->store($request->validated(), $request);
            return to_route('admin.posts.index')->with('success', 'Tạo bài viết thành công');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors('Lỗi khi tạo bài viết: ' . $e->getMessage());
        }
    }

    public function show(Post $post)
    {
        // Trả về view show, truyền dữ liệu $post
        return view('admin.posts.show', compact('post'));
    }


}
