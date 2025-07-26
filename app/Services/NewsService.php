<?php

namespace App\Services;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NewsService
{
    public function getPublishedPostsPaginated(int $perPage = 3): LengthAwarePaginator
    {
        return Post::where('status', PostStatus::PUBLISHED)
            ->where('publish_date', '<=', now())
            ->withCount(['likes', 'comments'])
            ->orderByDesc('publish_date')
            ->paginate($perPage);
    }

    public function isPostVisible(Post $post): bool
    {
        return $post->status === PostStatus::PUBLISHED;
    }
}
