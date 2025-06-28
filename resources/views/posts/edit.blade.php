@extends('layouts.app')

@section('title', 'Sửa bài viết')

@section('content')
<div class="container mt-4">
    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}"
                   class="form-control @error('title') is-invalid @enderror">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $post->slug) }}"
                   class="form-control @error('slug') is-invalid @enderror">
            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $post->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <textarea name="content" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ngày đăng</label>
            <input type="datetime-local" name="publish_date"
                   value="{{ old('publish_date', optional($post->publish_date)->format('Y-m-d\TH:i')) }}"
                   class="form-control @error('publish_date') is-invalid @enderror">
            @error('publish_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label><br>
            @if ($post->thumbnail)
                <img src="{{ $post->thumbnail }}" alt="Thumbnail" width="100" class="mb-2">
            @endif
            <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror">
            @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    $('.summernote').summernote({ height: 250 });
</script>
@endsection
