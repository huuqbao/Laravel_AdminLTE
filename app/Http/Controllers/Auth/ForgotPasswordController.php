<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\ForgotPasswordService;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function __construct(protected ForgotPasswordService $forgotPasswordService) {}

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        try {
            $this->forgotPasswordService->sendResetLink($request->validated()['email']);

            return back()->with('success', 'Link đặt lại mật khẩu đã được gửi.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Lỗi gửi link reset mật khẩu: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Đã xảy ra lỗi, vui lòng thử lại sau.'])->withInput();
        }
    }
}
