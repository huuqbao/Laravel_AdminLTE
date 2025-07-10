<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Enums\PostStatus;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'content', 'publish_date', 'status'
    ];

    protected $casts = [
        'publish_date' => 'datetime',
        'status' => PostStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    // Scopes (dùng enum thay vì số)
    public function scopeNew($query)
    {
        return $query->where('status', PostStatus::NEW);
    }

    public function scopeUpdated($query)
    {
        return $query->where('status', PostStatus::UPDATED);
    }

    public function scopePublished($query)
    {
        return $query->where('status', PostStatus::PUBLISHED);
    }

    public static function createUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 0;

        while (self::withTrashed()->where('slug', $slug)->exists()) {
            $count++;
            $slug = $originalSlug . '-' . $count;
        }

        return $slug;
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return $this->status->label(); 
    }

    public function getStatusClassAttribute()
    {
        return $this->status->badgeClass();
    }

    public function getStatusBadgeAttribute(): string
    {
        return sprintf('<span class="%s">%s</span>', $this->status_class, $this->status_label);
    }
}
