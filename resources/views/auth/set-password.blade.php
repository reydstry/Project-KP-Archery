<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Set Password</title>
</head>
<body>
    <h1>Buat Password</h1>
    <p>Akun kamu dibuat via Google. Buat password supaya bisa login manual pakai email.</p>

    @if ($errors->any())
        <div style="color:red">
            <ul>
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <div>
            <label>Password baru</label><br>
            <input name="password" type="password" required>
        </div>

        <div>
            <label>Konfirmasi password</label><br>
            <input name="password_confirmation" type="password" required>
        </div>

        <button type="submit">Simpan Password</button>
    </form>
</body>
</html>