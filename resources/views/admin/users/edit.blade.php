@extends('layouts.app')

@section('title', 'Cập nhật tài khoản')

@section('content')
<div class="container">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 mb-4">
            @include('layouts.sidebar-admin')
        </div>

        {{-- Main content --}}
        <div class="col-md-9">
            <h1>Cập nhật: {{ $user->name }}</h1>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Tên --}}
                <div class="mb-3">
                    <label for="first_name" class="form-label">Tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name"
                        value="{{ old('first_name', $user->first_name) }}">
                    @error('first_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Họ --}}
                <div class="mb-3">
                    <label for="last_name" class="form-label">Họ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="{{ old('last_name', $user->last_name) }}">
                    @error('last_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        id="email" name="email" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Địa chỉ --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="{{ old('address', $user->address) }}">
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Trạng thái --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        @foreach (\App\Enums\UserStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ $user->status === $status ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
