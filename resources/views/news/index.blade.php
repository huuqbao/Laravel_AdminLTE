@extends('layouts.app')

@section('title', 'Tin tức')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 border-start border-4 border-danger ps-2 fw-bold fs-3">TIN MỚI</h2>

    <div class="row">
        <div class="col-12">
            @forelse($posts as $post)
                <div class="d-flex mb-4 border-bottom pb-3 gap-3">
                    @if($post->getFirstMediaUrl('thumbnail'))
                        <a href="{{ route('news.show', $post->slug) }}">
                            <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" alt="{{ $post->title }}" class="img-fluid rounded" style="width: 180px; height: 110px; object-fit: cover;">
                        </a>
                    @endif

                    <div>
                        <h5 class="mb-1 fw-bold">
                            <a href="{{ route('news.show', $post->slug) }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="text-muted mb-1 small">🕒 {{ $post->publish_date->format('H:i d/m/Y') }}</p>
                        <p class="mb-0 text-secondary small">
                            {{ Str::limit($post->description, 140) }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Hiện chưa có bài viết nào được xuất bản.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>
@endsection
