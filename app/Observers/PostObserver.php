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
        $slug = $baseSlug;
        $count = 1;

        while (
            Post::where('slug', $slug)
                ->when($post->exists, fn ($query) => $query->where('id', '!=', $post->id))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$count}";
            $count++;
        }

        $post->slug = $slug;
    }

}
