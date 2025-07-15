<div class="row">
    @forelse($posts as $post)
        <div class="col-12 mb-3 border-bottom pb-3">
            <div class="d-flex gap-3">
                @if($post->getFirstMediaUrl('thumbnail'))
                    <a href="{{ route('news.show', $post->slug) }}">
                        <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" alt="{{ $post->title }}" class="rounded" style="width:180px; height:110px; object-fit:cover;">
                    </a>
                @endif
                <div>
                    <h5>
                        <a href="{{ route('news.show', $post->slug) }}" class="text-dark text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h5>
                </div>
            </div>
        </div>
    @empty
        <p>Không có bài viết nào.</p>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-3">
    {!! $posts->links() !!}
</div>
