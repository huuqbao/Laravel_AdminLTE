<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\PostObserver;

#[ObservedBy([PostObserver::class])]
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

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

}
