@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cập nhật bài viết</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}">
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Mô tả <span class="text-danger">*</span></label>
            <input type="text" name="description" class="form-control" value="{{ old('description', $post->description) }}">
            @error('description') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Nội dung <span class="text-danger">*</span></label>
            <textarea name="content" id="editor" class="form-control">{{ old('content', $post->content ?? '') }}</textarea>
            @error('content') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Ngày đăng <span class="text-danger">*</span></label>
            <input
                type="datetime-local"
                name="publish_date"
                class="form-control"
                value="{{ old('publish_date', $post->publish_date ? $post->publish_date->format('Y-m-d\TH:i') : '') }}"
            >
            @error('publish_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>


        @can('admin')
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    @foreach (\App\Enums\PostStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $post->status === $status ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endcan


        <div class="mb-3">
            <label>Hình đại diện <span class="text-danger">*</span></label>
            <input type="file" name="thumbnail" class="form-control">
            @if ($post->getFirstMediaUrl('thumbnail'))
                <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" width="80" class="mt-2 border rounded">
            @endif
            @error('thumbnail')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Cập nhật bài viết</button>
    </form>
</div>
@endsection


@push('scripts')
    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
