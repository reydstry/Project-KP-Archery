<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Coach Dashboard</title>
</head>
<body>
    <h1>Coach Dashboard (Dummy)</h1>
    <p>Hi, {{ $user->name }} ({{ $user->role->value }})</p>

    <ul>
        <li>Ringkasan: sesi hari ini, sesi upcoming</li>
        <li>Shortcut: manage training sessions, attendance</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
