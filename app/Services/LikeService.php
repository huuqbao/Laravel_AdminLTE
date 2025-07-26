<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeService
{
    public function toggleLike($likeable, Request $request): int
    {
        if (!Auth::check()) {
            abort(403, 'Bạn cần đăng nhập để thực hiện hành động này.');
        }

        $userId = Auth::id();

        $existingLike = $likeable->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            $existingLike->delete(); // Bỏ like nếu đã like
        } else {
            $likeable->likes()->create([
                'user_id'    => $userId,
                'ip_address' => $request->ip(), // log IP nếu cần
            ]);
        }

        return $likeable->likes()->count(); // Trả về tổng số lượt like hiện tại
    }
}
