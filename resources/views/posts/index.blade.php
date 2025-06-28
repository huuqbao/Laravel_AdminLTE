@extends('layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container mt-3">
    <div class="row">
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

                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
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


        {{-- Content --}}
        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📚 Danh sách bài viết</h5>
                    <div>
                        <a href="{{ route('posts.create') }}" class="btn btn-sm btn-success">+ Tạo mới</a>
                        <form action="{{ route('posts.destroyAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá tất cả?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️ Xoá tất cả</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <table id="postTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Tiêu đề</th>
                                <th>Ngày đăng</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $index => $post)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if ($post->thumbnail)
                                            <img src="{{ $post->thumbnail }}" alt="thumb" width="60">
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ optional($post->publish_date)->format('d/m/Y') ?? 'Chưa đặt' }}</td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                0 => ['label' => 'Mới', 'class' => 'secondary'],
                                                1 => ['label' => 'Đã cập nhật', 'class' => 'info'],
                                                2 => ['label' => 'Đã xuất bản', 'class' => 'success'],
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusMap[$post->status]['class'] }}">
                                            {{ $statusMap[$post->status]['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info">👁️</a>
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning">✏️</a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá bài viết này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">🗑️</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- DataTables --}}
@push('scripts')
    {{-- jQuery phải trước --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- DataTables sau --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#postTable').DataTable({
                paging: false,
                info: false,
                searching: false,
                ordering: false
            });
        });
    </script>
@endpush

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
@endpush
