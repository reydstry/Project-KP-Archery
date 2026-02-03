<!doctype html>
<html>
<head><meta charset="utf-8"><title>Forgot Password</title></head>
<body>
<h1>Lupa Password</h1>

@if (session('status'))
    <div style="color:green">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div style="color:red">
        <ul>@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <label>Email</label><br>
    <input type="email" name="email" value="{{ old('email') }}" required>
    <button type="submit">Kirim Link Reset</button>
</form>
</body>
</html>