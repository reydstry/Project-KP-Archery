<!doctype html>
<html>
<head><meta charset="utf-8"><title>Reset Password</title></head>
<body>
<h1>Reset Password</h1>

@if ($errors->any())
    <div style="color:red">
        <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email', $email) }}" required>

    <br><label>Password Baru</label><br>
    <input type="password" name="password" required>

    <br><label>Konfirmasi Password</label><br>
    <input type="password" name="password_confirmation" required>

    <br><button type="submit">Reset</button>
</form>
</body>
</html>