@extends('layouts.app')

@section('title', 'Tạo bài viết')

@section('content')
<div class="container mt-4">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tiêu đề --}}
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Slug --}}
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}">
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Mô tả --}}
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Nội dung --}}
        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea name="content" class="form-control summernote @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Ngày đăng --}}
        <div class="mb-3">
            <label for="publish_date" class="form-label">Ngày đăng</label>
            <input type="datetime-local" name="publish_date" class="form-control @error('publish_date') is-invalid @enderror" value="{{ old('publish_date') }}">
            @error('publish_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Ảnh đại diện --}}
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
{{-- Summernote --}}
<script>
    $('.summernote').summernote({
        height: 250
    });
</script>

{{-- Auto-generate slug --}}
<script>
    function toSlug(str) {
        return str
            .normalize('NFD')                     // Tách dấu
            .replace(/[\u0300-\u036f]/g, '')      // Xoá dấu
            .replace(/đ/g, 'd').replace(/Đ/g, 'D')
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')         // Xoá ký tự đặc biệt
            .replace(/\s+/g, '-')                 // khoảng trắng thành -
            .replace(/-+/g, '-');                 // bỏ - liên tiếp
    }

    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    titleInput.addEventListener('input', function () {
        if (!slugInput.dataset.manualEdit) {
            slugInput.value = toSlug(titleInput.value);
        }
    });

    slugInput.addEventListener('blur', function () {
        const slug = slugInput.value;
        if (!slug) return;
        fetch(`/posts/check-slug?slug=${encodeURIComponent(slug)}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    alert('Slug đã tồn tại, vui lòng chọn slug khác!');
                    slugInput.classList.add('is-invalid');
                } else {
                    slugInput.classList.remove('is-invalid');
                }
            });
    });
</script>
@endsection
