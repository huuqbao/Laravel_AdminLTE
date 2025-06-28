<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Middleware\CheckAccountStatus;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', CheckAccountStatus::class])->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth', CheckAccountStatus::class])->group(function () {
    Route::get('/posts', fn () => view('posts.index'))->name('posts.index');
});

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/profile-edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/profile-update', [UserController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)->except(['destroy']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/posts', [PostController::class, 'destroyAll'])->name('posts.destroyAll');
});

Route::middleware('auth')->group(function () {
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
});