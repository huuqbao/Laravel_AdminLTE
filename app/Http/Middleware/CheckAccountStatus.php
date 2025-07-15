<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserStatus;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        return match ($user->status) {
            UserStatus::PENDING  => $this->deny('Tài khoản đang chờ phê duyệt'),
            UserStatus::REJECTED => $this->deny('Tài khoản bị từ chối'),
            UserStatus::LOCKED   => $this->deny('Tài khoản đã bị khóa'),
            default => $next($request),
        };
    }

    protected function deny(string $message)
    {
        Auth::logout();
        return to_route('login')->withErrors(['email' => $message]);
    }
}
