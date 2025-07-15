@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Menu</strong>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('posts.index') }}" class="list-group-item list-group-item-action">
                        📄 Danh sách bài viết
                    </a>

                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                        📝 Cập nhật hồ sơ
                    </a>

                    <a href="{{ route('news.index') }}" class="list-group-item list-group-item-action">
                        📬 News
                    </a>

                    <a href="#" class="list-group-item list-group-item-action">
                        👤 Thông tin cá nhân
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

        <div class="col-md-8 d-flex align-items-center justify-content-center" style="min-height: 300px;">
            <h2 class="text-warning text-center">{{ Auth::user()->name }}</h2>
        </div>
    </div>
</div>
@endsection
