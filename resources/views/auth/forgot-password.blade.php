@extends('layouts.app')
@section('title', 'Quên mật khẩu')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh">
    <div class="card shadow-lg rounded-4 p-4" style="max-width: 500px; width: 100%;">
        <h4 class="mb-4 text-center fw-bold text-primary">Quên mật khẩu</h4>

        @if(session('success'))
            <div class="alert alert-success text-center rounded-3">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                    Địa chỉ Email <span class="text-danger">*</span>
                </label>
                <input type="text"
                       name="email"
                       id="email"
                       class="form-control form-control-lg"
                       value="{{ old('email') }}"
                       placeholder="Nhập địa chỉ email của bạn"
                       autofocus>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 mt-2 fw-semibold">
                Gửi liên kết đặt lại mật khẩu
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                <i class="bi bi-arrow-left"></i> Quay lại đăng nhập
            </a>
        </div>
    </div>
</div>
@endsection
