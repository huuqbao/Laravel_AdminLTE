<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <strong>Menu</strong>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('posts.index') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('posts.index') ? 'bg-success text-white' : '' }}">
            📄 Danh sách bài viết
        </a>
        <a href="{{ route('profile.edit') }}"
            class="list-group-item list-group-item-action {{ request()->routeIs('profile.edit') ? 'bg-success text-white' : '' }}">
            📝 Cập nhật hồ sơ
        </a>
        <a href="{{ route('news.index') }}" class="list-group-item list-group-item-action">📬 News</a>
    </div>
</div>
