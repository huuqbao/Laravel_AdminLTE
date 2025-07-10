<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post)
    {
        $this->setUniqueSlug($post);
    }

    public function updating(Post $post)
    {
        if ($post->isDirty('title')) {
            $this->setUniqueSlug($post);
        }
    }


    protected function setUniqueSlug(Post $post): void
    {
        $baseSlug = Str::slug($post->title);

        // Nếu slug trống thì đặt mặc định từ title
        $slug = $post->slug ?: $baseSlug;

        $originalSlug = $slug;
        $i = 1;

        while (
            Post::where('slug', $slug)
                ->when($post->exists, fn ($query) => $query->where('id', '!=', $post->id))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $i++;
        }

        $post->slug = $slug;
    }
}
