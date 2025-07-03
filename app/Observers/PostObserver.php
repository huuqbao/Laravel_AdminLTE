<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Str;

class PostObserver
{
    public function creating(Post $post)
    {
        if (empty($post->slug)) {
            $post->slug = Str::slug($post->title);
        }

        $originalSlug = $post->slug;
        $i = 1;

        while (Post::where('slug', $post->slug)->exists()) {
            $post->slug = $originalSlug . '-' . $i++;
        }
    }

    public function updating(Post $post)
    {
        if ($post->isDirty('title')) {
            $post->slug = Str::slug($post->title);
            $originalSlug = $post->slug;
            $i = 1;

            while (
                Post::where('slug', $post->slug)
                    ->where('id', '!=', $post->id)
                    ->exists()
            ) {
                $post->slug = $originalSlug . '-' . $i++;
            }
        }
    }
}
