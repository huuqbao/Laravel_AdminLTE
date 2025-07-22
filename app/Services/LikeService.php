<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeService
{
    public function toggleLike($likeable, Request $request): int
    {
        $query = $likeable->likes();

        $data = [
            'ip_address' => $request->ip(),
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            $exists = $query->where('user_id', $data['user_id'])->exists();
        } else {
            $exists = $query->where('ip_address', $data['ip_address'])->exists();
        }

        if (!$exists) {
            $likeable->likes()->create($data);
        }

        return $likeable->likes()->count();
    }
}
