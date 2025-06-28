<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'content', 'publish_date', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 0);
    }

    public function scopeUpdated($query)
    {
        return $query->where('status', 1);
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('publish_date');
    }
}