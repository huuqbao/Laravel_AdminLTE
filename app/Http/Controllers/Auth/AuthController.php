<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Enums\RoleStatus;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Exception;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'status'     => UserStatus::PENDING->value,
                'role'       => RoleStatus::USER->value,
            ]);

            Mail::to($user->email)->queue(new WelcomeMail($user));

            return to_route('login.form')->with('success', 'Đăng ký tài khoản thành công');

        } catch (QueryException $e) {
            // Trường hợp lỗi SQL (ví dụ email trùng)
            return back()->withErrors(['email' => 'Email đã tồn tại hoặc lỗi hệ thống.'])->withInput();
        } catch (Exception $e) {
            // Các lỗi khác
            return back()->withErrors(['error' => 'Đã xảy ra lỗi, vui lòng thử lại.'])->withInput();
        }
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            if ($this->authService->login($request->validated())) {
                return to_route('posts.index')->with('success', 'Đăng nhập thành công');
            }

            return to_route('login.form')->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ]);

        } catch (Exception $e) {
            return to_route('login.form')->withErrors([
                'error' => 'Đã xảy ra lỗi khi đăng nhập, vui lòng thử lại.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login.form');
    }
}
