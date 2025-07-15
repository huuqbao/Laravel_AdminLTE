@extends('layouts.app')

@section('title', 'Tin tức')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 border-start border-4 border-danger ps-2 fw-bold fs-3">TIN MỚI</h2>

    <table id="newsTable" class="table table-hover table-borderless">
        <thead>
            <tr>
                <th>STT</th>
                <th>Thumbnail</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Ngày đăng</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#newsTable', {
                processing: true,
                serverSide: true,
                ajax: '{{ route('news.data') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'thumbnail',
                        name: 'thumbnail',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return data ? `<img src="${data}" width="100" style="object-fit:cover;height:70px;border-radius:5px;">` : '';
                        }
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function (data, type, row) {
                            return `<a href="/news/${row.slug}" class="fw-bold text-dark text-decoration-none">${data}</a>`;
                        }
                    },
                    { data: 'description', name: 'description' },
                    {
                        data: 'publish_date',
                        name: 'publish_date',
                        render: function (data) {
                            const date = new Date(data);
                            return date.toLocaleString('vi-VN', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit',
                            });
                        }
                    }
                ],
                pageLength: 6,
                language: {
                    "emptyTable": "Không có bài viết nào",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy kết quả phù hợp",
                    "lengthMenu": "Hiển thị _MENU_ mục mỗi trang",
                    "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                    "infoFiltered": "(lọc từ tổng số _MAX_ mục)",
                }
            });
        });
    </script>
@endpush
