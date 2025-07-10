<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleStatus;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === RoleStatus::ADMIN->value) {
            return $next($request);
        }

        abort(404); // Không hiển thị gì nếu không phải admin
    }
}
