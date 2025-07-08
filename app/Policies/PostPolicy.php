<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Người dùng có thể xem bất kỳ bài viết nào
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Người dùng có thể xem bài viết cụ thể
     */
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Bất kỳ user nào đã đăng nhập đều có thể tạo bài viết
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Chỉ admin hoặc người tạo bài viết mới có thể sửa
     */
    public function update(User $user, Post $post): bool
    {
        return $user->is_admin || $user->id === $post->user_id;
    }

    /**
     * Chỉ admin hoặc người tạo bài viết mới có thể xóa
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->is_admin || $user->id === $post->user_id;
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
