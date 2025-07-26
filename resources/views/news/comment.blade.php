<div class="border rounded p-2 mb-2 comment-box" id="comment-{{ $comment->id }}">
    <strong>{{ $comment->user->name ?? 'Khách' }}:</strong>
    <p class="mb-1">{{ $comment->body }}</p>
    <div class="d-flex gap-2 align-items-center mb-2">
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

        {{-- Nút phản hồi --}}
        <button class="btn btn-sm btn-outline-secondary reply-btn" data-id="{{ $comment->id }}">
            💬 Phản hồi
        </button>

        {{-- Số lượt thích --}}
        <span id="like-count-{{ $comment->id }}">{{ $comment->likes->count() }}</span> lượt thích
    </div>

    {{-- Đệ quy hiển thị các phản hồi --}}
    @foreach ($comment->replies as $reply)
        <div class="border-start ps-3 ms-3 mt-2">
            @include('comment', ['comment' => $reply])
        </div>
    @endforeach
</div>
