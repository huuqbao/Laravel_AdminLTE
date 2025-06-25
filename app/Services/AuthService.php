<?php

namespace App\Services;

use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthService
{
    public function login(array $request): bool
    {
        if (Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ])) {
            $user = Auth::user(); // attempt check status
            
            match ($user->status) {
                UserStatus::Pending->value  => $this->failLogin('Tài khoản đang chờ phê duyệt.'),
                UserStatus::Rejected->value => $this->failLogin('Tài khoản đã bị từ chối.'),
                UserStatus::Locked->value   => $this->failLogin('Tài khoản đã bị khóa.'),
                default => true,
            };

            return true;
        }

        return false;

    }


    protected function failLogin(string $message): never
    {
        Auth::logout();
        throw ValidationException::withMessages(['email' => $message]);
    }
}
