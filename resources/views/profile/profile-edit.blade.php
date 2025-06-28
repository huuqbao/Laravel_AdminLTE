@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">

        {{-- Sidebar --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Menu</strong>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('posts.index') }}"
                        class="list-group-item list-group-item-action {{ request()->routeIs('posts.index') ? 'bg-success text-white' : '' }}">
                        📄 Danh sách bài viết
                    </a>

                    <a href="#"
                        class="list-group-item list-group-item-action">
                        👤 Thông tin cá nhân
                    </a>

                    <a href="{{ route('profile.edit') }}" 
                        class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'bg-success text-white' : '' }}">
                        👤 Cập nhật hồ sơ
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        ⚙️ Cài đặt
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        📬 Hộp thư đến
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        🛒 Lịch sử mua hàng
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        📊 Báo cáo hoạt động
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        🔒 Đổi mật khẩu
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        ❓ Trợ giúp & Hỗ trợ
                    </a>
                </div>
            </div>
        </div>

        {{-- Form cập nhật hồ sơ --}}
        <div class="col-md-9">
            <div class="card shadow p-4">
                <h3 class="text-center mb-4 text-primary">Cập nhật hồ sơ</h3>

                @if (session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf

                    {{-- Họ --}}
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Họ</label>
                        <input type="text" name="first_name" id="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tên --}}
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Tên</label>
                        <input type="text" name="last_name" id="last_name"
                            class="form-control @error('last_name') is-invalid @enderror"
                            value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Địa chỉ --}}
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea name="address" id="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary w-100">Cập nhật</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

