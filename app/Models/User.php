<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use App\Enums\RoleStatus;
use App\Enums\UserStatus;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'status',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    protected $casts = [
        'role' => RoleStatus::class,
        'status' => UserStatus::class,
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

      public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            UserStatus::PENDING => 'Chờ duyệt',
            UserStatus::APPROVED => 'Đã duyệt',
            UserStatus::REJECTED => 'Bị từ chối',
            UserStatus::LOCKED => 'Đã khóa',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            UserStatus::PENDING => 'badge bg-secondary',
            UserStatus::APPROVED => 'badge bg-success',
            UserStatus::REJECTED => 'badge bg-danger',
            UserStatus::LOCKED => 'badge bg-warning text-dark',
        };
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->last_name} {$this->first_name}") ?: $this->email;
    }
    
}
