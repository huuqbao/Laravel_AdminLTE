@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow p-4" style="min-width: 400px; max-width: 500px; width: 100%;">
        <h3 class="text-center text-primary mb-4">Đăng ký tài khoản</h3>

        {{-- Thông báo khi đăng ký thành công --}}
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Họ --}}
            <div class="mb-3">
                <label for="first_name" class="form-label">Họ</label>
                <input type="text" id="first_name" name="first_name"
                       class="form-control @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name') }}" required>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tên --}}
            <div class="mb-3">
                <label for="last_name" class="form-label">Tên</label>
                <input type="text" id="last_name" name="last_name"
                       class="form-control @error('last_name') is-invalid @enderror"
                       value="{{ old('last_name') }}" required>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>

            <p class="text-center mt-3 mb-0">
                Đã có tài khoản?
                <a href="{{ route('login') }}">Đăng nhập</a>
            </p>
        </form>
    </div>
</div>
@endsection
