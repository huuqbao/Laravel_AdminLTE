<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Dòng quan trọng

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

    // ✅ Accessor: $user->name
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // ✅ Mutator: tự mã hóa password nếu dùng create()
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}
