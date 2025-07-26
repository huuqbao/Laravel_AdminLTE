@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Tiêu đề --}}
            <h1 class="fw-bold mb-3">{{ $post->title }}</h1>

            {{-- Ngày đăng --}}
            <p class="text-muted small mb-4 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock">
                    <path d="M8 3.5a.5.5 0 0 1 .5.5v4l2.5 1.5a.5.5 0 0 1-.5.866L8 8.5V4a.5.5 0 0 1 .5-.5z"/>
                    <path d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                </svg>
                {{ $post->publish_date->format('H:i d/m/Y') }}
            </p>

            {{-- Thumbnail --}}
            @if($post->getFirstMediaUrl('thumbnail'))
                <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" class="img-fluid rounded mb-4 shadow-sm" alt="{{ $post->title }}">
            @endif

            {{-- Mô tả --}}
            <p class="lead text-secondary mb-4">{{ $post->description }}</p>

            {{-- Nội dung --}}
            <div class="article-content" style="line-height: 1.8; font-size: 1.05rem;">
                {!! $post->content !!}
            </div>

            {{-- Nút like --}}
            <div class="mt-4 d-flex align-items-center gap-2">
                <button id="like-post-btn" class="btn btn-sm d-flex align-items-center gap-1 {{ $userLiked ? 'btn-danger text-white' : 'btn-outline-danger' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         fill="{{ $userLiked ? 'white' : 'currentColor' }}"
                         class="bi bi-heart{{ $userLiked ? '-fill' : '' }}">
                        <path d="M8 2.748-.717-.737C5.6.281 2.514 1.522 1.4 4.058c-.523 1.2-.641 2.874.314 4.385C2.905 9.798 5.414 12.25 8 15c2.586-2.75 5.095-5.202 6.286-6.557.955-1.51.837-3.184.314-4.385C13.486 1.522 10.4.28 8.717 2.01L8 2.748z"/>
                    </svg>
                    Thích
                </button>
                <span id="like-post-count">{{ $post->likes_count }}</span> lượt thích
            </div>

            <hr>

            {{-- Form bình luận --}}
            <h5 class="mt-4">Bình luận <span class="text-danger">*</span></h5>

            {{-- Thông báo đang phản hồi --}}
            <div id="replying-to" class="alert alert-info d-none">
                Đang phản hồi <strong id="replying-user">ai đó</strong>
                <button type="button" class="btn btn-sm btn-link text-danger" id="cancel-reply">Hủy</button>
            </div>

            <form id="comment-form" class="mb-3">
                @csrf
                <input type="hidden" name="parent_id" id="parent_id">
                <div class="mb-2">
                    <textarea class="form-control" name="body" rows="3" placeholder="Nhập bình luận..."></textarea>
                    <div class="invalid-feedback d-none" id="comment-error">Vui lòng nhập bình luận.</div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Gửi</button>
            </form>

            {{-- Danh sách bình luận --}}
            <div id="comments-list">
                @foreach ($post->comments()->whereNull('parent_id')->latest()->get() as $comment)
                    @include('news.comment', ['comment' => $comment])
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
        let btn = $(this);
        let icon = btn.find('svg');
        let isLiked = btn.hasClass('btn-danger');

        $.post("{{ url('/posts/' . $post->id . '/like') }}", {
            _token: '{{ csrf_token() }}'
        }, function (data) {
            $('#like-post-count').text(data.count);

            if (isLiked) {
                btn.removeClass('btn-danger text-white').addClass('btn-outline-danger');
                icon.removeClass('bi-heart-fill').addClass('bi-heart').attr('fill', 'currentColor');
            } else {
                btn.removeClass('btn-outline-danger').addClass('btn-danger text-white');
                icon.removeClass('bi-heart').addClass('bi-heart-fill').attr('fill', 'white');
            }
        });
    });

    // Gửi bình luận
    $('#comment-form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let body = form.find('textarea[name="body"]');
        let errorDiv = $('#comment-error');
        let parentId = $('#parent_id').val();

        $.ajax({
            url: "{{ url('/posts/' . $post->id . '/comment') }}",
            method: "POST",
            data: form.serialize(),
            success: function (res) {
                location.reload(); // có thể cải thiện thành append cho mượt hơn
                $('#parent_id').val('');
                $('#replying-to').addClass('d-none');
                $('textarea[name="body"]').attr('placeholder', 'Nhập bình luận...');
            },
            error: function (xhr) {
                if (xhr.responseJSON?.errors?.body) {
                    errorDiv.text(xhr.responseJSON.errors.body[0]).removeClass('d-none');
                    body.addClass('is-invalid');
                } else {
                    alert('Đã xảy ra lỗi.');
                }
            }
        });
    });

    // Like bình luận
    $(document).on('click', '.like-comment-btn', function () {
        let btn = $(this);
        let id = btn.data('id');
        let icon = btn.find('svg');
        let isLiked = btn.hasClass('btn-danger');

        $.post(`/comments/${id}/like`, {
            _token: '{{ csrf_token() }}'
        }, function (data) {
            $(`#like-count-${id}`).text(data.count);

            if (isLiked) {
                btn.removeClass('btn-danger text-white').addClass('btn-outline-danger');
                icon.removeClass('bi-heart-fill').addClass('bi-heart').attr('fill', 'currentColor');
            } else {
                btn.removeClass('btn-outline-danger').addClass('btn-danger text-white');
                icon.removeClass('bi-heart').addClass('bi-heart-fill').attr('fill', 'white');
            }
        });
    });

    // Nhấn nút phản hồi
    $(document).on('click', '.reply-btn', function () {
        let id = $(this).data('id');
        let commentBox = $(this).closest('.comment-box');
        let userName = commentBox.find('strong').first().text().trim();

        $('#parent_id').val(id);
        $('textarea[name="body"]').focus().attr('placeholder', 'Phản hồi bình luận...');
        $('#replying-user').text(userName);
        $('#replying-to').removeClass('d-none');
    });

    // Hủy phản hồi
    $('#cancel-reply').on('click', function () {
        $('#parent_id').val('');
        $('textarea[name="body"]').attr('placeholder', 'Nhập bình luận...');
        $('#replying-to').addClass('d-none');
    });
</script>
@endpush
