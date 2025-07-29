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
            <h1>Cập nhật bài viết</h1>

            {{-- Thông báo thành công --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form cập nhật --}}
            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Tiêu đề --}}
                <div class="mb-3">
                    <label>Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}">
                    @error('title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Mô tả --}}
                <div class="mb-3">
                    <label>Mô tả <span class="text-danger">*</span></label>
                    <input type="text" name="description" class="form-control" value="{{ old('description', $post->description) }}">
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nội dung --}}
                <div class="mb-3">
                    <label>Nội dung <span class="text-danger">*</span></label>
                    <textarea name="content" id="editor" class="form-control">{{ old('content', $post->content ?? '') }}</textarea>
                    @error('content')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày đăng --}}
                <div class="mb-3">
                    <label>Ngày đăng <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="publish_date" class="form-control"
                        value="{{ old('publish_date', $post['publish_date']) }}">
                    @error('publish_date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Trạng thái (chỉ admin) --}}
                @if (auth()->user()?->role === \App\Enums\RoleStatus::ADMIN->value)
                    <div class="mb-3">
                        <label>Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-control">
                            <option value="0" {{ $post->status->value == 0 ? 'selected' : '' }}>Bài viết mới</option>
                            <option value="1" {{ $post->status->value == 1 ? 'selected' : '' }}>Được cập nhật</option>
                            <option value="2" {{ $post->status->value == 2 ? 'selected' : '' }}>Đã xuất bản</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- Thumbnail --}}
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

                {{-- Submit --}}
                <button type="submit" class="btn btn-success">Cập nhật bài viết</button>
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
