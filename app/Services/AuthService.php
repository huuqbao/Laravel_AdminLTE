<?php

namespace App\Services;

use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJobRegister;
use App\Enums\RoleStatus;
use Illuminate\Database\QueryException;

class AuthService 
{
    public function login(array $credentials): bool
    {
        if (!Auth::attempt($credentials)) {
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

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'status'     => UserStatus::PENDING->value,
                'role'       => RoleStatus::USER->value,
            ]);

            SendEmailJobRegister::dispatch($user);

            return $user;
        });
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

}
