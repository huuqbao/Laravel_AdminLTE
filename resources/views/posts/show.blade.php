@extends('layouts.app')

@section('title', 'Chi tiết bài viết')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ $post->title }}</h1>

    @if ($post->getFirstMediaUrl('thumbnail'))
        <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" class="img-fluid mb-3 rounded" alt="Thumbnail" style="max-width: 400px;">
    @endif

    <hr>
        <p class="text-muted">
            <strong>Ngày đăng:</strong>
            {{ $post->publish_date ? $post->publish_date->format('d/m/Y H:i') : 'Chưa đăng' }}
        </p>
    <hr>    

    <p><strong>Mô tả:</strong> {{ $post->description }}</p>

    <hr>
        <div>
            <p><strong>Nội dung:</strong>
            <div>{{ $post->content }}</div>
        </div>
    <hr>
    <p><strong>Trạng thái:</strong> <span class="{{ $post->status->badgeClass() }}">{{ $post->status->label() }}</span></p>

    <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
</div>
@endsection
