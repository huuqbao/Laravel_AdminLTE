<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\WelcomeMail;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Enums\RoleStatus;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Exception;
use App\Jobs\SendEmailJobRegister;
use Illuminate\Validation\ValidationException;

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
            $this->authService->register($request->validated());

            return to_route('login.form')->with('success', 'Đăng ký tài khoản thành công');
        } catch (QueryException $e) {
            return back()->withErrors(['email' => 'Email đã tồn tại hoặc lỗi hệ thống.'])->withInput();
        } catch (\Exception $e) {
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
            $this->authService->login($request->validated());

            $user =  Auth::user();

            if ($user->role === RoleStatus::ADMIN) {
                return to_route('admin.posts.index')->with('success', 'Đăng nhập thành công với quyền Admin');
            }

            return to_route('posts.index')->with('success', 'Đăng nhập thành công');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đăng nhập, vui lòng thử lại.']);
        }
    }


    public function logout(Request $request)
    {
        $this->authService->logout();
        return to_route('login.form');
    }

}
