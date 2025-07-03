@extends('layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container">
    <div class="row">
        {{-- Menu bên trái --}}
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Menu</strong>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('posts.index') }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs('posts.index') ? 'bg-success text-white' : '' }}">
                        📄 Danh sách bài viết
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">👤 Thông tin cá nhân</a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">📝 Cập nhật hồ sơ</a>
                    <a href="#" class="list-group-item list-group-item-action">⚙️ Cài đặt</a>
                    <a href="#" class="list-group-item list-group-item-action">📬 Hộp thư đến</a>
                    <a href="#" class="list-group-item list-group-item-action">🛒 Lịch sử mua hàng</a>
                    <a href="#" class="list-group-item list-group-item-action">📊 Báo cáo hoạt động</a>
                    <a href="#" class="list-group-item list-group-item-action">🔒 Đổi mật khẩu</a>
                    <a href="#" class="list-group-item list-group-item-action">❓ Trợ giúp & Hỗ trợ</a>
                    <a href="#" class="list-group-item list-group-item-action">🧾 Quản lý đơn hàng</a>
                    <a href="#" class="list-group-item list-group-item-action">📥 Nội dung đã lưu</a>
                    <a href="#" class="list-group-item list-group-item-action">🌐 Quản lý website</a>
                    <a href="#" class="list-group-item list-group-item-action">📢 Thông báo hệ thống</a>
                </div>
            </div>
        </div>

        {{-- Nội dung bên phải --}}
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Danh sách bài viết</h1>
                <div>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary me-2">+ Tạo bài viết</a>
                    <a href="#" id="delete-all-btn" class="btn btn-danger">🗑 Xoá tất cả</a>
                </div>
            </div>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Bảng bài viết --}}
            @include('posts.partials._table')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('delete-all-btn')?.addEventListener('click', function (e) {
        e.preventDefault();

        if (!confirm('Bạn có chắc chắn muốn xoá toàn bộ bài viết?')) return;

        fetch('{{ route('posts.destroyAll') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            // Reload danh sách trang 1
            fetch('{{ route('posts.index') }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const newDoc = parser.parseFromString(html, 'text/html');
                const newTable = newDoc.querySelector('#post-list');
                document.querySelector('#post-list').innerHTML = newTable.innerHTML;
            });
        })
        .catch(error => alert('Lỗi khi xoá tất cả: ' + error));
    });

    // Xử lý phân trang AJAX
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            const target = e.target.closest('.pagination a');
            if (target) {
                e.preventDefault();
                fetch(target.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    const newTable = newDoc.querySelector('#post-list');
                    document.querySelector('#post-list').innerHTML = newTable.innerHTML;
                })
                .catch(error => console.error('Lỗi phân trang:', error));
            }
        });
    });
</script>
@endpush
