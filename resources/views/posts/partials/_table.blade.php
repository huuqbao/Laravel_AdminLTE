<div id="post-list">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th style="width: 50px;">STT</th>
                <th style="width: 70px;">Thumbnail</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Ngày đăng</th>
                <th>Trạng thái</th>
                <th class="text-center" style="width: 130px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $index => $post)
                <tr>
                    <td>{{ $posts->firstItem() + $index }}</td>
                    <td>
                        @if ($post->getFirstMediaUrl('thumbnail'))
                            <img src="{{ $post->getFirstMediaUrl('thumbnail') }}" alt="Thumbnail" width="60">
                        @else
                            <span class="text-muted">Không có</span>
                        @endif
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($post->description, 50) }}</td>
                    <td>{{ $post->publish_date ? $post->publish_date->format('d/m/Y H:i') : 'Chưa đăng' }}</td>
                    <td>
                        <span class="{{ $post->status->badgeClass() }}">
                            {{ $post->status->label() }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa bài viết này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Không có bài viết nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3 d-flex justify-content-center">
        {!! $posts->links() !!}
    </div>
</div>
