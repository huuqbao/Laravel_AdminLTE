<div class="border rounded p-2 mb-2 comment-box" id="comment-{{ $comment->id }}">
    <strong>{{ $comment->user->name ?? 'Khách' }}:</strong>
    <p class="mb-1">{{ $comment->body }}</p>
    <div class="d-flex gap-2 align-items-center mb-2">
        @auth
            {{-- Nút like --}}
            <button class="btn btn-sm like-comment-btn d-flex align-items-center gap-1 
                {{ $comment->likes->where('user_id', auth()->id())->count() ? 'btn-danger text-white' : 'btn-outline-danger' }}"
                data-id="{{ $comment->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="{{ $comment->likes->where('user_id', auth()->id())->count() ? 'white' : 'currentColor' }}"
                    class="bi bi-heart{{ $comment->likes->where('user_id', auth()->id())->count() ? '-fill' : '' }}">
                    <path d="M8 2.748-.717-.737C5.6.281 2.514 1.522 1.4 4.058c-.523 1.2-.641 2.874.314 4.385C2.905 9.798 5.414 12.25 8 15c2.586-2.75 5.095-5.202 6.286-6.557.955-1.51.837-3.184.314-4.385C13.486 1.522 10.4.28 8.717 2.01L8 2.748z"/>
                </svg>
                Thích
            </button>
        @endauth

        {{-- Nút phản hồi --}}
        <button class="btn btn-sm btn-outline-secondary reply-btn" data-id="{{ $comment->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                <path d="M2 2a2 2 0 0 0-2 2v7.586l2.707-2.707A1 1 0 0 1 3.414 9H14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2zm12 6H3.414a2 2 0 0 0-1.414.586L0 11.586V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2z"/>
            </svg>
            Phản hồi
        </button>

        {{-- Số lượt thích --}}
        <span id="like-count-{{ $comment->id }}">{{ $comment->likes->count() }}</span> lượt thích
    </div>

    {{-- Đệ quy hiển thị các phản hồi --}}
    <div class="replies">
        @foreach ($comment->replies as $reply)
            <div>
                @include('news.comment', ['comment' => $reply])
            </div>
        @endforeach
    </div>

</div>
