<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleStatus;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === RoleStatus::ADMIN) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập');
    }

}