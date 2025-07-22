@extends('layouts.app')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 mb-4">
            @include('layouts.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Danh sách bài viết</h1>
                <div>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 5v14" />
                            <path d="M5 12h14" />
                        </svg>
                        Tạo bài viết
                    </a>

                    <a href="#" id="delete-all-btn" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="4" y1="7" x2="20" y2="7" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3h6v3" />
                        </svg>
                        Xoá tất cả
                    </a>
                </div>
            </div>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Tìm kiếm & lọc --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="customSearch" class="form-control" placeholder="Tìm tiêu đề hoặc mô tả...">
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-select">
                        <option value="">-- Tất cả trạng thái --</option>
                        @foreach(\App\Enums\PostStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="filterBtn" class="btn btn-secondary w-100">Lọc</button>
                </div>
            </div>

            {{-- DataTable --}}
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
            const table = new DataTable('#postTable', {
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: '{{ route('posts.index') }}',
                    data: function (d) {
                        d.custom_search = $('#customSearch').val();
                        d.status = $('#statusFilter').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'thumbnail',
                        orderable: false,
                        searchable: false,
                        render: data => data ? `<img src="${data}" width="60">` : ''
                    },
                    { data: 'title' },
                    { data: 'description' },
                    { data: 'publish_date' },
                    { data: 'status' },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: id => {
                            const showUrl = `/posts/${id}`;
                            const editUrl = `/posts/${id}/edit`;
                            const deleteUrl = `/posts/${id}`;

                            return `
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="${showUrl}" class="btn btn-info text-white" title="Xem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="2"/><path d="M22 12s-4-8-10-8S2 12 2 12s4 8 10 8 10-8 10-8"/></svg>
                                    </a>
                                    <a href="${editUrl}" class="btn btn-warning text-dark" title="Sửa">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" fill="none"><path d="M15 5l4 4l-11 11h-4v-4z"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3l-1.5 1.5-4-4 1.5-1.5z"/></svg>
                                    </a>
                                    <button type="button" class="btn btn-danger text-white delete-post-btn" data-url="${deleteUrl}" title="Xoá">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" fill="none"><line x1="4" y1="7" x2="20" y2="7"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-12"/><path d="M9 7V4h6v3"/></svg>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 3,
                language: {
                    emptyTable: "Không có bài viết nào",
                    search: "Tìm kiếm:",
                    zeroRecords: "Không tìm thấy kết quả phù hợp",
                    lengthMenu: "Hiển thị _MENU_ mục mỗi trang",
                    info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                    infoFiltered: "(lọc từ _MAX_ mục)"
                }
            });

            // Reload khi lọc
            $('#filterBtn').on('click', () => table.ajax.reload());

            // Xoá bài viết
            $('#postTable tbody').on('click', '.delete-post-btn', function (e) {
                e.preventDefault();
                const deleteUrl = $(this).data('url');
                if (!confirm('Bạn có chắc chắn muốn xóa bài viết này?')) return;

                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Đã xoá bài viết thành công.');
                    table.ajax.reload(null, false);
                })
                .catch(() => alert('Lỗi khi xoá bài viết!'));
            });

            // Xoá tất cả
            document.getElementById('delete-all-btn').addEventListener('click', function (e) {
                e.preventDefault();
                if (!confirm('Bạn có chắc chắn muốn xoá tất cả bài viết?')) return;

                fetch("{{ route('posts.destroyAll') }}", {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message || 'Đã xoá tất cả bài viết');
                    table.ajax.reload();
                })
                .catch(() => alert('Lỗi khi xoá tất cả bài viết'));
            });
        });
    </script>
@endpush
