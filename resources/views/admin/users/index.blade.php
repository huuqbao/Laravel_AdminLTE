@extends('layouts.app')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-4">
            @include('layouts.sidebar-admin')
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="h4 mb-0">Danh sách tài khoản</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <table id="usersTable" class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>ID</th> 
                                <th>Họ và Tên</th>
                                <th>Email</th>
                                <th>Địa chỉ</th>
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
    {{-- DataTables CSS & JS --}}
    <link rel="stylesheet" href="//cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <script src="//cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new DataTable('#usersTable', {
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.data') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'id', name: 'id' }, 
                    { data: 'name', name: 'name', searchable: true },
                    { data: 'email', name: 'email', searchable: true },
                    { data: 'address', name: 'address', searchable: false },
                    { data: 'status', name: 'status', searchable: true, orderable: false },
                    {
                        data: 'id',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function (id) {
                            let editUrl = '{{ route("admin.users.edit", ":id") }}'.replace(':id', id);
                            
                            return `
                                <a href="${editUrl}" class="btn btn-sm btn-warning text-dark" title="Sửa tài khoản">✏️ Sửa</a>
                            `;
                        }
                    }
                ],
                order: [[1, 'asc']], // Sắp xếp theo tên
                pageLength: 3,
                language: {
                    "emptyTable": "Không có tài khoản nào",
                    "search": "Tìm kiếm:",
                    "zeroRecords": "Không tìm thấy kết quả phù hợp",
                    "lengthMenu": "Hiển thị _MENU_ mục",
                    "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                    "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                    "infoFiltered": "(lọc từ _MAX_ mục)",
                }
            });
        });
    </script>
@endpush