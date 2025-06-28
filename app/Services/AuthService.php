<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials): bool
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false; // Sai email hoặc mật khẩu
        }

        // Check status trước khi login
        match ($user->status) {
            UserStatus::PENDING->value  => $this->failLogin('Tài khoản đang chờ phê duyệt.'),
            UserStatus::REJECTED->value => $this->failLogin('Tài khoản đã bị từ chối.'),
            UserStatus::LOCKED->value   => $this->failLogin('Tài khoản đã bị khóa.'),
            default => null,
        };

        Auth::login($user); // Chỉ login nếu status hợp lệ

        return true;
    }

    protected function failLogin(string $message): never
    {
        Auth::logout(); // Đăng xuất ngay lập tức nếu status không hợp lệ
        throw ValidationException::withMessages(['email' => $message]);
    }
}
