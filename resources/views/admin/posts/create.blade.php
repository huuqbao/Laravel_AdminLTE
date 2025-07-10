@extends('layouts.app')

@section('title', 'Tạo bài viết mới - Admin')

@section('content')
<div class="container">
    <h1 class="mb-4">✍️ Tạo bài viết mới</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title">Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="300">
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description">Mô tả <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" maxlength="500">{{ old('description') }}</textarea>
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="content">Nội dung <span class="text-danger">*</span></label>
            <textarea name="content" class="form-control summernote" rows="6">{{ old('content') }}</textarea>
            @error('content') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="publish_date">Ngày đăng <span class="text-danger">*</span></label>
            <input type="datetime-local" name="publish_date" class="form-control" value="{{ old('publish_date') }}">
            @error('publish_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="thumbnail">Ảnh đại diện <span class="text-danger">*</span></label>
            <input type="file" name="thumbnail" class="form-control">
            @error('thumbnail') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="status">Trạng thái <span class="text-danger">*</span></label>
            <select name="status" class="form-control">
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>🕓 Mới</option>
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>📝 Đã cập nhật</option>
                <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>✅ Đã xuất bản</option>
            </select>
            @error('status') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">💾 Lưu bài viết</button>
    </form>
</div>
@endsection

@push('scripts')
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Nhập nội dung bài viết...',
        });
    });
</script>
@endpush
