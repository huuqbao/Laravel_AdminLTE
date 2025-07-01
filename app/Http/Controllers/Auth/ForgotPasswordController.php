<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Exception;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            $status = Password::sendResetLink($request->validated());

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('success', 'Link đặt lại mật khẩu đã được gửi.');
            }

            return back()->withErrors(['email' => __($status)])->withInput();

        } catch (Exception $e) {
            Log::error('Lỗi gửi link reset mật khẩu: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Đã xảy ra lỗi, vui lòng thử lại sau.'])->withInput();
        }
    }
}
