<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ auth()->user()?->can('admin') ? route('admin.dashboard') : route('home') }}">
            AdminLTE
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto"></ul>

            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a href="{{ route('login.form') }}" class="nav-link">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register.form') }}" class="nav-link">Đăng ký</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Xin chào, {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            {{-- Chỉ hiển thị link này cho admin --}}
                            @can('admin')
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Trang quản trị
                                </a>
                            @endcan
                            
                            {{-- Link này cho tất cả user đã đăng nhập --}}
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                 <i class="fas fa-user-edit"></i> Hồ sơ
                            </a>

                            <div class="dropdown-divider"></div>

                            {{-- Link đăng xuất --}}
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush