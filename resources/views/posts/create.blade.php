@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tạo bài viết</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}">
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="content">Nội dung</label>
            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="10" style="resize: vertical;">{{ old('content') }}</textarea>
            @error('content') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Ngày đăng</label>
            <input
                type="datetime-local"
                name="publish_date"
                class="form-control @error('publish_date') is-invalid @enderror"
                value="{{ old('publish_date', isset($post) && $post->publish_date ? $post->publish_date->format('Y-m-d\TH:i') : '') }}"
            >
            @error('publish_date') <div class="text-danger">{{ $message }}</div> @enderror          
        </div>

        <div class="mb-3">
            <label>Hình đại diện</label>
            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
            @error('thumbnail') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Tạo bài viết</button>
    </form>
</div>
@endsection

@push('scripts')
<!-- Summernote CSS + JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200
        });
    });
</script>
@endpush
