<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div style="color:green">{{ session('status') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div>
            <label>Email</label><br>
            <input name="email" type="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Password</label><br>
            <input name="password" type="password" required>
        </div>

        <div>
            <label>
                <input type="checkbox" name="remember" value="1"> Remember me
            </label>
        </div>

        <button type="submit">Login</button>
    </form>

    <div style="margin-top:12px">
        <a href="{{ route('password.request') }}">Lupa password?</a>
    </div>

    <hr>

    <div>
        <a href="{{ route('auth.google.redirect') }}">Login dengan Google</a>
    </div>

    <p>Belum punya akun? <a href="{{ route('register') }}">Register</a></p>
</body>
</html>