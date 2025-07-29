@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-3 mb-4">
            @include('layouts.sidebar')
        </div>

        {{-- Main content --}}
        <div class="col-md-9">
            <h1>Tạo bài viết</h1>

            {{-- Form tạo bài viết --}}
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Tiêu đề --}}
                <div class="mb-3">
                    <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label for="description">Mô tả <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nội dung --}}
                <div class="mb-3">
                    <label for="content">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="content" id="editor">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày đăng --}}
                <div class="mb-3">
                    <label for="publish_date">Ngày đăng</label>
                    <input
                        type="datetime-local"
                        name="publish_date"
                        class="form-control"
                        value="{{ old('publish_date') ? format_datetime_local(old('publish_date')) : '' }}"
                    >
                    @error('publish_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ảnh đại diện --}}
                <div class="mb-3">
                    <label for="thumbnail">Ảnh đại diện</label>
                    <input type="file" name="thumbnail" class="form-control">
                    @error('thumbnail')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary">Tạo bài viết</button>
            </form>
        </div>
    </div>
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
