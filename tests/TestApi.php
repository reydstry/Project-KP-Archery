<?php

declare(strict_types=1);

$baseUrl = 'http://127.0.0.1:8000';

function request(string $method, string $url, array $headers = [], ?array $json = null): array
{
    $ch = curl_init($url);

    $httpHeaders = array_merge([
        'Accept: application/json',
    ], $headers);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => strtoupper($method),
        CURLOPT_HTTPHEADER     => $httpHeaders,
        CURLOPT_TIMEOUT        => 30,
    ]);

    if ($json !== null) {
        $payload = json_encode($json, JSON_UNESCAPED_SLASHES);
        $httpHeaders[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    }

    $body = curl_exec($ch);
    $err  = curl_error($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($body === false) {
        throw new RuntimeException("cURL error: {$err}");
    }

    $decoded = json_decode($body, true);
    return [$code, $decoded ?? $body];
}

// 1) LOGIN
[$code, $res] = request('POST', $baseUrl . '/api/auth/login', [], [
    'email' => 'admin@club.test',
    'password' => 'password',
]);

echo "LOGIN HTTP {$code}\n";
print_r($res);

if (!is_array($res) || empty($res['token'])) {
    exit("Token tidak ditemukan dari response login.\n");
}

$token = $res['token'];
$authHeader = ["Authorization: Bearer {$token}"];

// 2) ME
[$code, $res] = request('GET', $baseUrl . '/api/auth/me', $authHeader);
echo "\nME HTTP {$code}\n";
print_r($res);

// 3) ADMIN PING (harus 200 kalau role admin, 403 kalau bukan)
[$code, $res] = request('GET', $baseUrl . '/api/admin/ping', $authHeader);
echo "\nADMIN PING HTTP {$code}\n";
print_r($res);

// 4) LOGOUT
[$code, $res] = request('POST', $baseUrl . '/api/auth/logout', $authHeader);
echo "\nLOGOUT HTTP {$code}\n";
print_r($res);

// 5) ME lagi (harusnya 401 kalau token sudah direvoke)
[$code, $res] = request('GET', $baseUrl . '/api/auth/me', $authHeader);
echo "\nME AFTER LOGOUT HTTP {$code}\n";
print_r($res);