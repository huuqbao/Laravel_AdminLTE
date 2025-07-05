<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);//gui token tu URL vao view
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
            $status = Password::reset(
                $request->validated(),
                function (User $user, string $password) { //closure ham an
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? to_route('login.form')->with('success', 'Đặt lại mật khẩu thành công.')
                : back()->withErrors(['email' => __($status)])->withInput();

        } catch (Exception $e) {
            Log::error('Lỗi đặt lại mật khẩu: ' . $e->getMessage());

            return back()->withErrors([
                'email' => 'Có lỗi xảy ra khi đặt lại mật khẩu. Vui lòng thử lại sau.'
            ])->withInput();
        }
    }
}
