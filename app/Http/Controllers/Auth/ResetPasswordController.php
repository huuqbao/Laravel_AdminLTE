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
use App\Services\ResetPasswordService;

class ResetPasswordController extends Controller
{
    public function __construct(protected ResetPasswordService $resetPasswordService) {}

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        try {
            $this->resetPasswordService->reset($request->validated());

            return to_route('login.form')->with('success', 'Đặt lại mật khẩu thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Lỗi đặt lại mật khẩu: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Có lỗi xảy ra khi đặt lại mật khẩu. Vui lòng thử lại sau.'
            ])->withInput();
        }
    }
}
