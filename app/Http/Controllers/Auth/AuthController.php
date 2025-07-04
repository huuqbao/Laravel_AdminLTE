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

class AuthController extends Controller
{
    protected AuthService $authService;  //Khai báo một thuộc tính để lưu đối tượng AuthService

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)  //tham số truyền vào hàm là 1 đối tượng thuộc RegisterRequest
    {
        DB::beginTransaction();

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

            DB::commit();

            return to_route('login.form')->with('success', 'Đăng ký tài khoản thành công');

        } catch (QueryException $e) {
            DB::rollBack(); // rollback khi lỗi SQL
            return back()->withErrors(['email' => 'Email đã tồn tại hoặc lỗi hệ thống.'])->withInput();
        } catch (Exception $e) {
            DB::rollBack(); // rollback khi lỗi khác
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

            return to_route('posts.index')->with('success', 'Đăng nhập thành công');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đăng nhập, vui lòng thử lại.']);
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); //xoa du lieu
        $request->session()->regenerateToken(); // Tao token moi

        return to_route('login.form');
    }
}
