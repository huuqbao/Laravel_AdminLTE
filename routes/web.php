<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
use App\Http\Middleware\CheckAccountStatus;

// Trang chính
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Trang chủ sau đăng nhập
Route::get('/home', fn() => view('home')) // hàm ẩn danh fn() => view('home')
    ->middleware(['auth', CheckAccountStatus::class])
    ->name('home');

// Nhóm route cho khách (chưa login)
Route::middleware('guest')->group(function () {
    // Đăng ký
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form'); 
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Đăng nhập
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    // Quên mật khẩu & reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Nhóm route sau khi đăng nhập (và qua middleware kiểm tra trạng thái tài khoản)
Route::middleware(['auth', CheckAccountStatus::class])->group(function () {
    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Quản lý bài viết
    Route::delete('/posts/delete-all', [PostController::class, 'destroyAll'])->name('posts.destroyAll');
    Route::resource('posts', PostController::class); // đầy đủ CRUD
    // Bao gồm:
    // GET     /posts              => posts.index   (Hiển thị danh sách bài viết)
    // GET     /posts/create       => posts.create  (Hiển thị form tạo bài viết)
    // POST    /posts              => posts.store   (Lưu bài viết mới)
    // GET     /posts/{post}       => posts.show    (Hiển thị chi tiết bài viết)
    // GET     /posts/{post}/edit  => posts.edit    (Hiển thị form chỉnh sửa)
    // PUT     /posts/{post}       => posts.update  (Cập nhật bài viết)
    // DELETE  /posts/{post}       => posts.destroy (Xoá bài viết)
});

// Profile user (chỉ cần đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [UserController::class, 'update'])->name('profile.update');
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{post:slug}', [NewsController::class, 'show'])->name('news.show');