@extends('layouts.app')

@section('title', 'Chi tiết bài viết (Admin)')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $post->title }}</h1>

    @if ($post->getFirstMediaUrl('thumbnail'))
        <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" class="img-fluid mb-3 rounded" alt="Thumbnail" style="max-width: 400px;">
    @endif

    <p class="text-muted">
        <strong>Ngày đăng:</strong>
        {{ $post->publish_date ? $post->publish_date->format('d/m/Y H:i') : 'Chưa đăng' }}
    </p>

    <p><strong>Trạng thái:</strong>
        <span class="{{ $post->status->badgeClass() }}">
            {{ $post->status->label() }}
        </span>
    </p>

    <p><strong>Người tạo:</strong> {{ $post->user?->name ?? 'Không rõ' }}</p>

    <hr>

    <p><strong>Mô tả:</strong> {{ $post->description }}</p>

    <div class="mt-3">
        <p><strong>Nội dung:</strong></p>
        <div>{!! nl2br(e($post->content)) !!}</div>
    </div>

    <hr>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning">✏️ Chỉnh sửa</a>

        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Xoá bài viết</button>
        </form>
    </div>
</div>
@endsection
