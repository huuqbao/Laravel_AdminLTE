@can('admin')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <strong>Menu Admin</strong>
        </div>
        <div class="list-group list-group-flush">
            <a href="{{ route('admin.posts.index') }}"
               class="list-group-item list-group-item-action {{ request()->routeIs('admin.posts.*') ? 'bg-success text-white' : '' }}">
                📝 Quản lý bài viết
            </a>
            
            <a href="{{ route('admin.users.index') }}" 
               class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.*') ? 'bg-success text-white' : '' }}">
                👥 Quản lý người dùng
            </a>
        </div>
    </div>
@endcan