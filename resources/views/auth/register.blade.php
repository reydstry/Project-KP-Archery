<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf
        <div>
            <label>Name</label><br>
            <input name="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label>Email</label><br>
            <input name="email" type="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label>Password</label><br>
            <input name="password" type="password" required>
        </div>
        <div>
            <label>Confirm Password</label><br>
            <input name="password_confirmation" type="password" required>
        </div>
        <button type="submit">Register</button>
    </form>

    <hr>

    <a href="{{ route('auth.google.redirect') }}">Daftar/Login dengan Google</a>

    <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
</body>
</html>