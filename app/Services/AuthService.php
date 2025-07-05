<?php

namespace App\Services;

use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService //kiem tra dang nhap
{
    public function login(array $credentials): bool
    {
        if (!Auth::attempt([ //kiem tra dang nhap thanh cong hay that bai
            'email' => $credentials['email'], 
            'password' => $credentials['password'],
        ])) {
            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);
        }
        $user = Auth::user();

        // Check status sau khi đăng nhập
        match ($user->status) {
            UserStatus::PENDING->value  => $this->failLogin('Tài khoản đang chờ phê duyệt.'),
            UserStatus::REJECTED->value => $this->failLogin('Tài khoản đã bị từ chối.'),
            UserStatus::LOCKED->value   => $this->failLogin('Tài khoản đã bị khóa.'),
            default => null,
        };

        return true; // Đăng nhập hợp lệ
    }

    protected function failLogin(string $message): never
    {
        Auth::logout(); // Đăng xuất ngay lập tức nếu status không hợp lệ
        throw ValidationException::withMessages(['email' => $message]);
    }
}
