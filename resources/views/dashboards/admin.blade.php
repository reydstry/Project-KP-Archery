<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard (Dummy)</h1>
    <p>Hi, {{ $user->name }} ({{ $user->role->value }})</p>

    <ul>
        <li>Ringkasan: pending member, active member, packages, news, achievements</li>
        <li>Shortcut: kelola member, coach, paket</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
