<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'content',
        'publish_date',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor: thumbnail
    public function getThumbnailAttribute(): string|null
    {
        return $this->getFirstMediaUrl(collectionName: 'thumbnails') ?: null;
    }

    // Accessor: trạng thái hiển thị
    public function getStatusBadgeAttribute(): array
    {
        return [
            'label' => match($this->status) {
                0 => 'Mới',
                1 => 'Đã cập nhật',
                2 => 'Đã xuất bản',
                default => 'Không xác định',
            },
            'class' => match($this->status) {
                0 => 'secondary',
                1 => 'info',
                2 => 'success',
                default => 'dark',
            },
        ];
    }

    // Scopes
    public function scopeNew(Builder $query)
    {
        return $query->where('status', 0);
    }

    public function scopeUpdated(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function scopePublished(Builder $query)
    {
        return $query->whereNotNull('publish_date');
    }

    // Optional: Tự động tạo slug nếu chưa có (có thể xử lý trong sự kiện model hoặc observer)
    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }
}
