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
                            <th>STT</th>
                            <th>Thumbnail</th>
                            <th style="width: 20%;">Tiêu đề</th> 
                            <th style="width: 40%;">Mô tả</th> 
                            <th>Ngày đăng</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#postTable', {
                processing: true,
                serverSide: true,
                ajax: '{{ route('posts.data') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, 
                    {
                        data: 'thumbnail',
                        name: 'thumbnail',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return data ? `<img src="${data}" width="60">` : '';
                        }
                    },
                    { data: 'title', name: 'title', searchable: true },
                    { data: 'description', name: 'description', searchable: true },
                    {
                        data: 'publish_date',
                        name: 'publish_date',
                        searchable: true,
                        render: function (data) {
                            if (!data) return '';
                            const date = new Date(data);
                            return date.toLocaleString('vi-VN', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                            });
                        }
                    },
                    { data: 'status', name: 'status', searchable: true },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function (id) {
                            let showUrl = `/posts/${id}`;
                            let editUrl = `/posts/${id}/edit`;
                            let deleteUrl = `/posts/${id}`;

                            return `
                                <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
                                    <a href="${showUrl}" class="btn btn-info text-white" title="Xem bài viết">👁</a>
                                    <a href="${editUrl}" class="btn btn-warning text-dark" title="Sửa bài viết">✏️</a>
                                    <form action="${deleteUrl}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');"
                                        style="display:inline;">
                                        <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger text-white" title="Xoá bài viết">🗑</button>
                                    </form>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 3,
                //pagingType: "full_numbers",
                language: {
                    "emptyTable": "Không có bài viết nào",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy kết quả phù hợp",
                    "lengthMenu": "Hiển thị _MENU_ mục mỗi trang",
                    "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                    "infoFiltered": "(được lọc từ tổng số _MAX_ mục)",
                }
            });
        });

        document.getElementById('delete-all-btn').addEventListener('click', function (e) {
            e.preventDefault();

            if (!confirm('Bạn có chắc chắn muốn xoá tất cả bài viết?')) return;

            fetch("{{ route('posts.destroyAll') }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Xoá thất bại');
                return response.json();
            })
            .then(data => {
                alert(data.message || 'Đã xoá tất cả bài viết');
                $('#postTable').DataTable().ajax.reload(); 
            })
            .catch(error => {
                alert('Lỗi khi xoá bài viết');
                console.error(error);
            });
        });
    </script>

@endpush
