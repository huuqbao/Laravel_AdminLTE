<form method="POST" action="{{ route('login') }}">
    @csrf
    <input name="email" value="{{ old('email') }}" placeholder="Email">
    @error('email') <span>{{ $message }}</span> @enderror

    <input name="password" type="password" placeholder="Password">
    @error('password') <span>{{ $message }}</span> @enderror

    <button type="submit">Đăng nhập</button>
</form>
@if (session('success')) <div>{{ session('success') }}</div> @endif
