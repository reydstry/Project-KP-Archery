<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Dashboard</title>
</head>
<body>
    <h1>Member Dashboard (Dummy)</h1>
    <p>Hi, {{ $user->name }} ({{ $user->role->value }})</p>

    <ul>
        <li>Ringkasan: paket aktif, sisa kuota, booking terbaru</li>
        <li>Shortcut: booking sesi latihan</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
