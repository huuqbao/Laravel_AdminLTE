<?php

use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Middleware\CheckAccountStatus;


// Trang chính
Route::get('/', fn() => view('welcome'))->name('welcome');

// Trang chủ sau đăng nhập
Route::get('/home', fn() => view('home'))
    ->middleware(['auth', 'check.account', 'can:user']) 
    ->name('home');

// 1. Nhóm route cho khách (chưa login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form'); 
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});


// 2. Nhóm route sau khi login + kiểm tra trạng thái tài khoản
Route::middleware(['auth', 'check.account', 'can:user'])->group(function () {
    Route::get('/posts/data', [PostController::class, 'getData'])->name('posts.data');
    Route::delete('/posts/delete-all', [PostController::class, 'destroyAll'])->name('posts.destroyAll');
    
    Route::resource('posts', PostController::class);
    // Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    // Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    // Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    // Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    // Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    // Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});


// 3. Profile user (chỉ cần login, không cần check trạng thái)
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [UserController::class, 'update'])->name('profile.update');
});


// 4. Trang tin tức (ai cũng xem được)
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{post:slug}', [NewsController::class, 'show'])->name('news.show');

//5. Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin', 'check.account']) 
    ->group(function () {

        Route::get('/', fn() => to_route('admin.users.index'))->name('dashboard');

        // Nhóm route quản lý bài viết
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('data', [AdminPostController::class, 'getData'])->name('data');
            Route::patch('{post}/status', [AdminPostController::class, 'updateStatus'])->name('updateStatus');
            Route::delete('/', [AdminPostController::class, 'destroyAll'])->name('destroyAll');
            Route::resource('/', AdminPostController::class)->parameters(['' => 'post']);
        });

        // Nhóm route quản lý người dùng
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('data', [AdminUserController::class, 'data'])->name('data');
            Route::resource('/', AdminUserController::class)->only(['index', 'edit', 'update'])->parameters(['' => 'user']);
        });
    });


// 6. Đăng xuất 
Route::middleware(['auth', 'check.account'])->post('/logout', [AuthController::class, 'logout'])->name('logout');

// 7. Like + comment
Route::post('/posts/{post}/like', [LikeController::class, 'likePost']);
Route::post('/comments/{comment}/like', [LikeController::class, 'likeComment']);
Route::post('/posts/{post}/comment', [CommentController::class, 'store']);



