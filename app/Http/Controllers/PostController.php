<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
        // Áp dụng policy middleware
        $this->middleware('can:update,post')->only(['edit', 'update']);
        $this->middleware('can:delete,post')->only(['destroy']);
    }

    /**
     * Hiển thị danh sách bài viết.
     * Sử dụng DataTables client-side => dùng get() thay vì paginate().
     */
    public function index()
    {
        $today = Carbon::now();

        // Lấy toàn bộ bài viết đã đăng
        $posts = Post::where('user_id', Auth::id()) // 👈 Chỉ lấy bài viết của user hiện tại
            ->where('publish_date', '<=', $today)
            ->orderBy('publish_date', 'desc')
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
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
        $this->authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
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
}
