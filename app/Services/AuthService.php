<?php

namespace App\Services;

use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService 
{
    public function login(array $credentials): bool
    {
        if (!Auth::attempt([ 
            'email' => $credentials['email'], 
            'password' => $credentials['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);
        }
        $user = Auth::user();

        match ($user->status) {
            UserStatus::PENDING->value  => $this->failLogin('Tài khoản đang chờ phê duyệt.'),
            UserStatus::REJECTED->value => $this->failLogin('Tài khoản đã bị từ chối.'),
            UserStatus::LOCKED->value   => $this->failLogin('Tài khoản đã bị khóa.'),
            default => null,
        };

        return true; 
    }

    protected function failLogin(string $message): never
    {
        Auth::logout(); 
        throw ValidationException::withMessages(['email' => $message]);
    }
}
