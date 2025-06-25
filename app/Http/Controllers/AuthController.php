<?php

namespace App\Http\Controllers;

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


class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('posts.index');// thanh to router
        }

        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => $validated['password'],     // 'password'   => Hash::make($validated['password']),
            'status'     => UserStatus::Pending->value,
            'role'       => 'user',
        ]);
        
        Auth::login($user); 

        Mail::to($user->email)->send(new WelcomeMail($user)); //job + queue

        return redirect()->route('login.form')->with('success', 'Đăng ký tài khoản thành công');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('posts.index');
        }
                
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if($this->authService->login($request->validated())) {
            return redirect()->route('posts.index')->with('success', 'Đăng nhập thành công');
        }

        return redirect()->route('login.form')->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
