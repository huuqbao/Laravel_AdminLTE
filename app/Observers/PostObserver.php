<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    public function creating(Post $post): void
    {
        // Slug sẽ được set sau khi post được tạo, nên để trống
        $post->slug = '';
    }

    public function created(Post $post): void
    {
        // Sau khi có ID, mới hash ID làm slug
        if (empty($post->slug)) {
            $post->slug = base_convert($post->id, 10, 36);
            $post->saveQuietly(); // Không kích hoạt lại created()
        }
    }

    public function updating(Post $post): void
    {
        // Nếu muốn thay đổi slug khi đổi title
        if ($post->isDirty('title')) {
            $post->slug = base_convert($post->id, 10, 36);
        }
    }
}
