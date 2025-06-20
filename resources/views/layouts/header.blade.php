<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">AdminLTE</a>
        <div class="collapse navbar-collapse justify-content-end">
            @auth
                <span class="me-3">Xin chào, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                </form>
            @else
                <a href="{{ route('login.form') }}" class="btn btn-outline-primary btn-sm me-2">Đăng nhập</a>
                <a href="{{ route('register.form') }}" class="btn btn-outline-success btn-sm">Đăng ký</a>
            @endauth
        </div>
    </div>
</nav>
