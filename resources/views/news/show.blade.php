@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- Tiêu đề và mô tả -->
            <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
            <p class="text-muted small mb-4">🕒 {{ $post->publish_date->format('H:i d/m/Y') }}</p>

            @if($post->getFirstMediaUrl('thumbnail'))
                <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" class="img-fluid rounded mb-4 shadow-sm" alt="{{ $post->title }}">
            @endif

            <p class="lead text-secondary mb-4">
                {{ $post->description }}
            </p>

            <!-- Nội dung -->
            <div class="article-content" style="line-height: 1.8; font-size: 1.05rem;">
                <div>{!! $post->content !!}</div>
            </div>

            <!-- Like bài viết -->
            <div class="mt-4 d-flex align-items-center gap-2">
                <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" id="like-post-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514 1.522 1.4 4.058c-.523 1.23-.641 2.875.314 4.385.92 1.45 2.834 2.905 6.286 5.357 3.452-2.452 5.366-3.907 6.286-5.357.955-1.51.838-3.155.314-4.385C13.486 1.522 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                    </svg>
                    Thích
                </button>
                <span id="like-post-count">{{ $post->likes()->count() }}</span> lượt thích
            </div>

            <hr>

            <!-- Form bình luận -->
            <h5 class="mt-4">
                Bình luận <span class="text-danger">*</span>
            </h5>
            <form id="comment-form" class="mb-3">
                @csrf
                <div class="mb-2">
                    <textarea class="form-control" name="body" rows="3" placeholder="Nhập bình luận..."></textarea>
                    <div class="invalid-feedback d-none" id="comment-error">Vui lòng nhập bình luận.</div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Gửi</button>
            </form>

            <!-- Danh sách bình luận -->
            <div id="comments-list">
                @foreach ($post->comments as $comment)
                    <div class="border rounded p-2 mb-2">
                        <strong>{{ $comment->user->name ?? 'Khách' }}:</strong>
                        <p class="mb-1">{{ $comment->body }}</p>
                        <div>
                            <button class="btn btn-sm btn-outline-danger like-comment-btn d-flex align-items-center gap-1" data-id="{{ $comment->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                    <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 3.905.92 1.37 2.834 2.857 6.286 5.385 3.452-2.528 5.365-4.015 6.286-5.385.955-1.405.838-2.882.314-3.905C13.486.878 10.4.28 8.717 2.01L8 2.748Z"/>
                                    <path d="M8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15Z"/>
                                </svg>
                                Thích
                            </button>
                            <span id="like-count-{{ $comment->id }}">{{ $comment->likes->count() }}</span> lượt thích
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Like bài viết
    $('#like-post-btn').on('click', function () {
        $.post("{{ url('/posts/' . $post->id . '/like') }}", {
            _token: '{{ csrf_token() }}'
        }, function (data) {
            $('#like-post-count').text(data.count);
        });
    });

    // Gửi bình luận
    $('#comment-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let bodyInput = form.find('textarea[name="body"]');
        let errorDiv = $('#comment-error');

        $.ajax({
            url: "{{ url('/posts/' . $post->id . '/comment') }}",
            method: "POST",
            data: form.serialize(),
            success: function (res) {
                let commentHtml = `
                    <div class="border rounded p-2 mb-2">
                        <strong>${res.comment.user}</strong>
                        <p class="mb-1">${res.comment.body}</p>
                        <div>
                            <button class="btn btn-sm btn-outline-danger like-comment-btn" data-id="${res.comment.id}">❤️ Thích</button>
                            <span id="like-count-${res.comment.id}">0</span> lượt thích
                        </div>
                    </div>
                `;
                $('#comments-list').prepend(commentHtml);
                form[0].reset();
                errorDiv.addClass('d-none');
                bodyInput.removeClass('is-invalid');
            },
            error: function (xhr) {
                if (xhr.responseJSON?.errors?.body) {
                    errorDiv.text(xhr.responseJSON.errors.body[0]).removeClass('d-none');
                    bodyInput.addClass('is-invalid');
                } else {
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                }
            }
        });
    });

    // Like comment
    $(document).on('click', '.like-comment-btn', function () {
        let id = $(this).data('id');
        $.post(`/comments/${id}/like`, {
            _token: '{{ csrf_token() }}'
        }, function (data) {
            $(`#like-count-${id}`).text(data.count);
        });
    });
</script>
@endpush
