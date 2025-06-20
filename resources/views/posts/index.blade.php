@extends('layouts.master')

@section('title', 'Danh sách bài viết')

@section('content')
<div class="container mt-3">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i>Danh sách bài viết</h4>
            <a href="#" class="btn btn-outline-light btn-sm fw-bold px-3 rounded-pill shadow-sm">
                + Tạo mới
            </a>
        </div>

        <div class="card-body">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-person-circle me-2 fs-5"></i>
                <div>
                    Xin chào <strong>{{ Auth::user()->name }}</strong>! Dưới đây là danh sách bài viết của bạn.
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col" class="text-end">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Nếu không có dữ liệu --}}
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">📝 Chưa có bài viết nào.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
