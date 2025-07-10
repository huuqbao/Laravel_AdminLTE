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
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">📝 Cập nhật hồ sơ</a>
                    <a href="{{ route('news.index') }}" class="list-group-item list-group-item-action">📬 News</a>
                    <a href="#" class="list-group-item list-group-item-action">👤 Thông tin cá nhân</a>
                    <a href="#" class="list-group-item list-group-item-action">⚙️ Cài đặt</a>
                    <a href="#" class="list-group-item list-group-item-action">🛒 Lịch sử mua hàng</a>
                    <a href="#" class="list-group-item list-group-item-action">📊 Báo cáo hoạt động</a>
                    <a href="#" class="list-group-item list-group-item-action">🔒 Đổi mật khẩu</a>
                    <a href="#" class="list-group-item list-group-item-action">❓ Trợ giúp & Hỗ trợ</a>
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

            {{-- Bảng danh sách bài viết --}}
            <div id="post-list">
                <table id="postTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th style="width: 70px;">Thumbnail</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả</th>
                            <th>Ngày đăng</th>
                            <th>Trạng thái</th>
                            <th class="text-center" style="width: 130px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $index => $post)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if ($post->getFirstMediaUrl('thumbnail'))
                                        <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" alt="Thumbnail" width="60">
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                                <td>{{ $post->title }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($post->description, 50) }}</td>
                                <td>{{ $post->publish_date ? $post->publish_date->format('d/m/Y H:i') : 'Chưa đăng' }}</td>
                                <td>
                                    <span class="{{ $post->status->badgeClass() }}">
                                        {{ $post->status->label() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-info" target="_blank">👁 Xem</a>
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">✏️ Sửa</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">🗑 Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Không có bài viết nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{--  DataTables v2.3.2 --}}
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    @if ($posts->count()) {{-- Kiểm tra xem có bài viết không, Chỉ chạy đoạn code nếu có dữ liệu --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ✅ Khởi tạo DataTable khi có bài viết
                new DataTable('#postTable', {
                    pageLength: 3,
                    language: { 
                        "emptyTable": "Không có bài viết nào",
                        "search": "Tìm kiếm:",
                        "zeroRecords": "Không tìm thấy kết quả phù hợp",
                        "lengthMenu": "Hiển thị _MENU_ mục mỗi trang",
                        "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    } 
                });

                // ✅ Xử lý xoá tất cả bài viết
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
                        location.reload();
                    })
                    .catch(error => alert('Lỗi khi xoá tất cả: ' + error));
                });
            });
        </script>
    @endif
@endpush
