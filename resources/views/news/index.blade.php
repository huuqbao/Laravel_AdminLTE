@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 border-start border-4 border-danger ps-2 fw-bold fs-3">TIN MỚI</h2>

    <div id="news-list">
        @foreach ($posts as $post)
            <div class="mb-4 border-bottom pb-3">
                <h4>
                    <a href="{{ route('news.show', $post) }}" class="text-dark text-decoration-none">
                        {{ $post->title }}
                    </a>
                </h4>

                <p class="text-muted small d-flex align-items-center gap-3 flex-wrap">
                    <span class="d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                            <path d="M8 3.5a.5.5 0 0 1 .5.5v4l2.5 1.5a.5.5 0 0 1-.5.866L8 8.5V4a.5.5 0 0 1 .5-.5z"/>
                            <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16zm0-1A7 7 0 1 0 8 1a7 7 0 0 0 0 14z"/>
                        </svg>
                        {{ $post->publish_date->format('H:i d/m/Y') }}
                    </span>

                    <span class="d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                            <path d="m8 2.748-.717-.737C5.6.281 2.514 1.522 1.4 4.058c-.523 1.2-.641 2.874.314 4.385C2.905 9.798 5.414 12.25 8 15c2.586-2.75 5.095-5.202 6.286-6.557.955-1.51.837-3.184.314-4.385C13.486 1.522 10.4.28 8.717 2.01L8 2.748z"/>
                        </svg>
                        {{ $post->likes_count ?? 0 }} lượt thích
                    </span>

                    <span class="d-inline-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                            <path d="M2 2a2 2 0 0 0-2 2v7.586l2-2A2 2 0 0 1 3.414 9H14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2z"/>
                            <path d="M3 5.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        {{ $post->comments_count ?? 0 }} bình luận
                    </span>
                </p>

                @if ($post->getFirstMediaUrl('thumbnail'))
                    <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" alt="{{ $post->title }}" class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: cover;">
                @endif

                <p class="text-secondary">{{ $post->description }}</p>
                <a href="{{ route('news.show', $post) }}" class="btn btn-sm btn-outline-primary">Đọc tiếp</a>
            </div>
        @endforeach

        {{-- Phân trang --}}
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchPage(page);
    });

    function fetchPage(page) {
        $.ajax({
            url: "?page=" + page,
            type: "GET",
            success: function(data) {
                $('#news-list').html(data);
            },
            error: function() {
                alert("Không thể tải dữ liệu.");
            }
        });
    }
</script>
@endpush
