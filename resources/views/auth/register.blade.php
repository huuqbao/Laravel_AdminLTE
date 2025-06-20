<form action="{{ route('register') }}" method="POST">
    @csrf

    <input name="first_name" value="{{ old('first_name') }}" placeholder="First name">
    @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror

    <input name="last_name" value="{{ old('last_name') }}" placeholder="Last name">
    @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror

    <input name="email" type="email" value="{{ old('email') }}" placeholder="Email">
    @error('email') <div class="text-danger">{{ $message }}</div> @enderror

    <input name="password" type="password" placeholder="Password">
    @error('password') <div class="text-danger">{{ $message }}</div> @enderror

    <input name="password_confirmation" type="password" placeholder="Confirm Password">

    <button type="submit">Đăng ký</button>
</form>
