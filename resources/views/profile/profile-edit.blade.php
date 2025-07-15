@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4 text-primary">Cập nhật hồ sơ</h3>

                @if (session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label for="first_name" class="form-label">Họ <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name', $user->first_name) }}">
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Tên <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name', $user->last_name) }}">
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ </span></label>
                        <textarea name="address" id="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
