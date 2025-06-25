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
            return redirect()->route('login');
        }

        return match ($user->status) {
            UserStatus::Pending->value  => $this->deny('Tài khoản đang chờ phê duyệt'),
            UserStatus::Rejected->value => $this->deny('Tài khoản bị từ chối'),
            UserStatus::Locked->value   => $this->deny('Tài khoản đã bị khóa'),
            default => $next($request),
        };
    }

    protected function deny(string $message)
    {
        Auth::logout(); 
        return redirect()->route('login')->withErrors(['email' => $message]);
    }
}
