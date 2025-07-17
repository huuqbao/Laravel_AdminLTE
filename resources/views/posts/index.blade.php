@extends('layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-4">
            @include('layouts.sidebar')
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Danh sách bài viết</h1>
                <div>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary me-2">+ Tạo bài viết</a>
                    <a href="#" id="delete-all-btn" class="btn btn-danger">🗑 Xoá tất cả</a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
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
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Khởi tạo DataTable
            var table = new DataTable('#postTable', {
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
                    { data: 'title', name: 'title' },
                    { data: 'description', name: 'description' },
                    {
                        data: 'publish_date',
                        name: 'publish_date',
                        render: function (data) {
                            if (!data) return '';
                            const date = new Date(data);
                            return date.toLocaleString('vi-VN', {
                                day: '2-digit', month: '2-digit', year: 'numeric',
                                hour: '2-digit', minute: '2-digit'
                            });
                        }
                    },
                    { data: 'status', name: 'status' },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function (id) {
                            let showUrl = `/posts/${id}`;
                            let editUrl = `/posts/${id}/edit`;
                            // URL để gửi yêu cầu DELETE
                            let deleteUrl = `/posts/${id}`;

                            return `
                                <div class="btn-group btn-group-sm" role="group" aria-label="Actions">
                                    <a href="${showUrl}" class="btn btn-info text-white" title="Xem bài viết">👁</a>
                                    <a href="${editUrl}" class="btn btn-warning text-dark" title="Sửa bài viết">✏️</a>                                    
                                    <button type="button" class="btn btn-danger text-white delete-post-btn" 
                                            data-url="${deleteUrl}" title="Xoá bài viết">
                                        🗑
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 3,
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

            // Event listener cho nút xóa từng bài viết 
            $('#postTable tbody').on('click', '.delete-post-btn', function (e) {
                e.preventDefault();
                
                // Lấy URL từ thuộc tính data-url của nút được click
                const deleteUrl = $(this).data('url');

                if (!confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
                    return;
                }

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Xóa thất bại. Vui lòng thử lại.');
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message || 'Đã xoá bài viết thành công.');
                    table.ajax.reload(null, false); 
                })
                .catch(error => {
                    alert('Lỗi khi xoá bài viết!');
                    console.error(error);
                });
            });

            // Xoa tat ca
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
                    table.ajax.reload(); 
                })
                .catch(error => {
                    alert('Lỗi khi xoá bài viết');
                    console.error(error);
                });
            });
        });
    </script>
@endpush
