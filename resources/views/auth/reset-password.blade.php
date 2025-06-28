@extends('layouts.app')
@section('title', 'Đặt lại mật khẩu')

@section('content')
<div class="container mt-5">
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email">Email</label>
            <input name="email" id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password">Mật khẩu mới</label>
            <input name="password" id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input name="password_confirmation" id="password_confirmation" type="password"
                   class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100">Đặt lại mật khẩu</button>
    </form>
</div>
@endsection
