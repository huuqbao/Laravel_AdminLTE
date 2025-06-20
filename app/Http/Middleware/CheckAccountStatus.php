<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->status === 0) {
            return redirect()->route('login')->withErrors(['email' => 'Tài khoản đang chờ phê duyệt']);
        }

        if ($user->status === 2) {
            return redirect()->route('login')->withErrors(['email' => 'Tài khoản bị từ chối']);
        }

        if ($user->status === 3) {
            return redirect()->route('login')->withErrors(['email' => 'Tài khoản đã bị khóa']);
        }

        return $next($request);
    }
}
