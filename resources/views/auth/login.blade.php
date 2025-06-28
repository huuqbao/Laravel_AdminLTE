@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <form method="POST" action="{{ route('login') }}" class="card shadow p-4 bg-white" style="min-width: 400px; max-width: 500px;">
        @csrf

        <h3 class="text-center text-primary mb-4">Đăng nhập</h3>

        {{-- Hiển thị thông báo thành công --}}
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input name="email" type="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Nhập email" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mật khẩu --}}
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
            <input name="password" type="password" id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Nhập mật khẩu" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        {{-- Nút đăng nhập --}}
        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

        {{-- Quên mật khẩu --}}
        <div class="text-center mt-2">
            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
        </div>

        {{-- Đăng ký --}}
        <div class="text-center mt-3">
            <a href="{{ route('register.form') }}">Chưa có tài khoản? Đăng ký ngay</a>
        </div>
    </form>
</div>
@endsection
