@extends('layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container mt-3">
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
                    <a href="#" class="list-group-item list-group-item-action">👤 Thông tin cá nhân</a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">👤 Cập nhật hồ sơ</a>
                    <a href="#" class="list-group-item list-group-item-action">⚙️ Cài đặt</a>
                    <a href="#" class="list-group-item list-group-item-action">📬 Hộp thư đến</a>
                    <a href="#" class="list-group-item list-group-item-action">🛒 Lịch sử mua hàng</a>
                    <a href="#" class="list-group-item list-group-item-action">📊 Báo cáo hoạt động</a>
                    <a href="#" class="list-group-item list-group-item-action">🔒 Đổi mật khẩu</a>
                    <a href="#" class="list-group-item list-group-item-action">❓ Trợ giúp & Hỗ trợ</a>
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

                        <!-- Nút mở modal xoá tất cả -->
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteAllModal">
                            🗑️ Xoá tất cả
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <table id="postTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Tiêu đề</th>
                                <th>Mô tả</th>
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
                                            <img src="{{ $post->thumbnail }}" width="60" class="rounded border">
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->description }}</td>
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
                                        {{-- Xem --}}
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                            👁️
                                        </a>

                                        {{-- Sửa --}}
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-warning me-1" title="Chỉnh sửa">
                                            ✏️
                                        </a>

                                        {{-- Nút mở modal xoá --}}
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                title="Xoá"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $post->id }}">
                                            🗑️
                                        </button>

                                        {{-- Modal xác nhận xoá --}}
                                        <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $post->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4">
                                                    <div class="modal-header bg-danger text-white rounded-top">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $post->id }}">🗑️ Xác nhận xoá</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <p class="mb-0 fs-5">
                                                            Bạn có chắc chắn muốn xoá bài viết <strong class="text-danger">"{{ $post->title }}"</strong> không?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xoá</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

<!-- Modal xoá tất cả -->
<div class="modal fade" id="confirmDeleteAllModal" tabindex="-1" aria-labelledby="confirmDeleteAllModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xoá tất cả</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc muốn xoá <strong>tất cả bài viết</strong> không?
            </div>
            <div class="modal-footer">
                <form action="{{ route('posts.destroyAll') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-danger">Xoá tất cả</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts --}}
@push('scripts')
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- DataTables --}}
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

                                        {{-- @if ($post->thumbnail)
                                            <img src="{{ asset('storage/' . $post->thumbnail) }}" width="100">
                                        @else
                                            <p>Chưa có ảnh</p>
                                        @endifp --}}