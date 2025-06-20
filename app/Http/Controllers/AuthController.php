<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auths.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => $validated['password'], 
            'status'     => 0, 
            'role'       => 'user',
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));

        return redirect()->route('login.form')->with('success', 'Đăng ký tài khoản thành công');
    }

    public function showLogin()
    {
        return view('auths.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
        }

        if ($user->status == 0) {
            return back()->withErrors(['email' => 'Tài khoản đang chờ phê duyệt']);
        } elseif ($user->status == 2) {
            return back()->withErrors(['email' => 'Tài khoản bị từ chối']);
        } elseif ($user->status == 3) {
            return back()->withErrors(['email' => 'Tài khoản đã bị khoá']);
        }

        Auth::login($user);
        return redirect()->route('posts.index')->with('success', 'Đăng nhập thành công');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}
