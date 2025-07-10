<a href="{{ route('posts.show', $post->id) }}" class="btn btn-sm btn-info" target="_blank">👁 Xem</a>
<a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-warning">✏️ Sửa</a>
<form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
      onsubmit="return confirm('Bạn có chắc chắn muốn xoá bài viết này?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-danger">🗑 Xoá</button>
</form>
