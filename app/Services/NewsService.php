<?php

namespace App\Services;

use App\Models\Post;
use App\Enums\PostStatus;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsService
{
    public function getPublishedNews(int $perPage = 4)
    {
        return Post::where('status', PostStatus::PUBLISHED)
                    ->where('publish_date', '<=', Carbon::now())
                    ->orderByDesc('publish_date')
                    ->paginate($perPage);
    }

    public function getDatatableNews(Request $request): array
    {
        $query = Post::query()
            ->where('status', PostStatus::PUBLISHED)
            ->where('publish_date', '<=', Carbon::now());

        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 6);

        $columns = ['id', 'thumbnail', 'title', 'description', 'publish_date']; 
        $order = $request->input('order.0', ['column' => 0, 'dir' => 'desc']);
        $columnIndex = (int) ($order['column'] ?? 0);
        $sortColumn = $columns[$columnIndex] ?? 'id';
        $sortDir = $order['dir'] === 'asc' ? 'asc' : 'desc';

        $posts = $query->orderBy($sortColumn, $sortDir)
            ->paginate($length, ['*'], 'page', intval($start / $length) + 1);

        $data = $posts->map(function ($post, $index) use ($start) {
            return [
                'DT_RowIndex' => $start + $index + 1,
                'thumbnail' => $post->getFirstMediaUrl('thumbnail'),
                'title' => Str::limit($post->title, 50),
                'description' => Str::limit($post->description, 100),
                'publish_date' => $post->publish_date,
                'slug' => $post->slug,
            ];
        })->toArray();

        return [
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ];
    }
}
