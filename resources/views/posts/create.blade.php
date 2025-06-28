@extends('layouts.app')

@section('title', 'Tạo bài viết')

@section('content')
<div class="container mt-4">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea name="content" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="publish_date" class="form-label">Ngày đăng</label>
            <input type="datetime-local" name="publish_date" class="form-control @error('publish_date') is-invalid @enderror" value="{{ old('publish_date') }}">
            @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Ảnh đại diện</label>
            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
            @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $('.summernote').summernote({
        height: 250
    });
</script>
@endsection