@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow p-4" style="min-width: 400px; max-width: 500px; width: 100%;">
        <h3 class="text-center text-primary mb-4">Đăng ký tài khoản</h3>

        {{-- Hiển thị thông báo đăng ký thành công --}}
        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
            @csrf

            {{-- Họ --}}
            <div class="mb-3">
                <label for="first_name" class="form-label">Họ <span class="text-danger">*</span></label>
                <input name="first_name" id="first_name" type="text"
                    class="form-control @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name') }}" placeholder="Nhập họ">
                <div class="invalid-feedback"></div>
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tên --}}
            <div class="mb-3">
                <label for="last_name" class="form-label">Tên <span class="text-danger">*</span></label>
                <input name="last_name" id="last_name" type="text"
                    class="form-control @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name') }}" placeholder="Nhập tên">
                <div class="invalid-feedback"></div>
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input name="email" id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="Nhập email">
                <div class="invalid-feedback"></div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                <input name="password" id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Nhập mật khẩu">
                <div class="invalid-feedback"></div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Xác nhận mật khẩu --}}
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input name="password_confirmation" id="password_confirmation" type="password"
                    class="form-control" placeholder="Nhập lại mật khẩu">
                <div class="invalid-feedback"></div>
            </div>

            {{-- Nút đăng ký --}}
            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>

            {{-- Link đăng nhập --}}
            <p class="text-center mt-3 mb-0">
                Đã có tài khoản?
                <a href="{{ route('login') }}">Đăng nhập</a>
            </p>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        // Lấy các input
        const inputs = {
            first_name: document.getElementById('first_name'),
            last_name: document.getElementById('last_name'),
            email: document.getElementById('email'),
            password: document.getElementById('password'),
            password_confirmation: document.getElementById('password_confirmation')
        };

        // Xóa hết lỗi cũ trước khi kiểm tra
        Object.values(inputs).forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.nextElementSibling;
            if(feedback && feedback.classList.contains('invalid-feedback')){
                feedback.textContent = '';
            }
        });

        let hasError = false;

        // Kiểm tra từng trường
        if (!inputs.first_name.value.trim()) {
            inputs.first_name.classList.add('is-invalid');
            inputs.first_name.nextElementSibling.textContent = "Vui lòng nhập họ.";
            hasError = true;
        }
        if (!inputs.last_name.value.trim()) {
            inputs.last_name.classList.add('is-invalid');
            inputs.last_name.nextElementSibling.textContent = "Vui lòng nhập tên.";
            hasError = true;
        }
        if (!inputs.email.value.trim()) {
            inputs.email.classList.add('is-invalid');
            inputs.email.nextElementSibling.textContent = "Vui lòng nhập email.";
            hasError = true;
        }
        if (!inputs.password.value.trim()) {
            inputs.password.classList.add('is-invalid');
            inputs.password.nextElementSibling.textContent = "Vui lòng nhập mật khẩu.";
            hasError = true;
        }
        if (!inputs.password_confirmation.value.trim()) {
            inputs.password_confirmation.classList.add('is-invalid');
            inputs.password_confirmation.nextElementSibling.textContent = "Vui lòng nhập xác nhận mật khẩu.";
            hasError = true;
        }

        // Nếu không có lỗi, submit form thật
        if (!hasError) {
            this.submit();
        }
    });
</script>
@endpush
