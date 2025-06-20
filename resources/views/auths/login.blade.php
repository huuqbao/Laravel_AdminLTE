@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4" style="min-width: 400px; max-width: 500px;">
        <h3 class="text-center text-primary mb-4">Đăng nhập</h3>

        {{-- Thông báo đăng ký thành công --}}
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input name="email" type="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="Nhập email" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu:</label>
                <input name="password" type="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Nhập mật khẩu" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>

            <p class="text-center mt-3 mb-0">
                Chưa có tài khoản?
                <a href="{{ route('register.form') }}">Đăng ký ngay</a>
            </p>
        </form>
    </div>
</div>
@endsection
