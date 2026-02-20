<?php

namespace App\Gateways;

use App\Contracts\WhatsAppGatewayInterface;
use DateTimeInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WablasGateway implements WhatsAppGatewayInterface
{
    public function sendMessage(string $phone, string $message): array
    {
        $url = $this->url('send_message_path');
        $authMeta = $this->authorizationMeta();

        if ($this->isV2Path('send_message_path')) {
            $payload = [
                'data' => [
                    [
                        'phone' => $phone,
                        'message' => $message,
                    ],
                ],
            ];

            $response = Http::timeout($this->timeout())
                ->withHeaders($this->headers(true))
                ->post($url, $payload);
        } else {
            $response = Http::timeout($this->timeout())
                ->asForm()
                ->withHeaders($this->headers(false))
                ->post($url, [
                    'phone' => $phone,
                    'message' => $message,
                ]);
        }

        return [
            'provider' => 'wablas',
            'phone' => $phone,
            'message' => $message,
            'requested_url' => $url,
            'auth_meta' => $authMeta,
            'success' => $response->successful(),
            'status_code' => $response->status(),
            'body' => $this->decodeBody($response->body()),
        ];
    }

    public function sendBulk(array $phones, string $message): array
    {
        $url = $this->url('send_bulk_path');
        $authMeta = $this->authorizationMeta();

        $payload = $this->isV2Path('send_bulk_path')
            ? [
                'data' => array_map(fn (string $phone) => [
                    'phone' => $phone,
                    'message' => $message,
                ], $phones),
            ]
            : [
                'phone' => implode(',', $phones),
                'message' => $message,
            ];

        $client = Http::timeout($this->timeout());

        if ($this->isV2Path('send_bulk_path')) {
            $client = $client->withHeaders($this->headers(true));
        } else {
            $client = $client->asForm()->withHeaders($this->headers(false));
        }

        $response = $client->post($url, $payload);

        if ($response->successful()) {
            $body = $this->decodeBody($response->body());

            return array_map(fn (string $phone) => [
                'provider' => 'wablas',
                'phone' => $phone,
                'message' => $message,
                'requested_url' => $url,
                'auth_meta' => $authMeta,
                'success' => true,
                'status_code' => $response->status(),
                'body' => $body,
            ], $phones);
        }

        return array_map(fn (string $phone) => [
            'provider' => 'wablas',
            'phone' => $phone,
            'message' => $message,
            'requested_url' => $url,
            'auth_meta' => $authMeta,
            'success' => false,
            'status_code' => $response->status(),
            'body' => $this->decodeBody($response->body()),
        ], $phones);
    }

    public function scheduleMessage(array $phones, string $message, DateTimeInterface $sendAt): array
    {
        $url = $this->url('schedule_path');
        $authMeta = $this->authorizationMeta();

        if ($this->isV2Path('schedule_path')) {
            $payload = [
                'data' => array_map(fn (string $phone) => [
                    'category' => 'text',
                    'phone' => $phone,
                    'scheduled_at' => $sendAt->format('Y-m-d H:i:s'),
                    'text' => $message,
                ], $phones),
            ];

            $response = Http::timeout($this->timeout())
                ->withHeaders($this->headers(true))
                ->post($url, $payload);
        } else {
            $payload = [
                'phone' => implode(',', $phones),
                'message' => $message,
                'date' => $sendAt->format('Y-m-d'),
                'time' => $sendAt->format('H:i:s'),
                'timezone' => 'Asia/Jakarta',
            ];

            $response = Http::timeout($this->timeout())
                ->asForm()
                ->withHeaders($this->headers(false))
                ->post($url, $payload);
        }

        $body = $this->decodeBody($response->body());

        return array_map(fn (string $phone) => [
            'provider' => 'wablas',
            'phone' => $phone,
            'message' => $message,
            'requested_url' => $url,
            'auth_meta' => $authMeta,
            'success' => $response->successful(),
            'status_code' => $response->status(),
            'body' => $body,
        ], $phones);
    }

    private function url(string $pathKey): string
    {
        $baseUrl = $this->normalizeBaseUrl((string) $this->setting('base_url', config('whatsapp.drivers.wablas.base_url')));
        $path = '/' . ltrim((string) config("whatsapp.drivers.wablas.{$pathKey}"), '/');

        return $baseUrl . $path;
    }

    private function normalizeBaseUrl(string $baseUrl): string
    {
        $baseUrl = rtrim(trim($baseUrl), '/');

        if ($baseUrl === '') {
            return rtrim((string) config('whatsapp.drivers.wablas.base_url'), '/');
        }

        $parts = parse_url($baseUrl);

        if ($parts === false) {
            return $baseUrl;
        }

        $path = $parts['path'] ?? '';

        // Wablas API base URL typically ends with "/api". If user inputs only the host (e.g. https://jkt.wablas.com),
        // auto-append "/api" to avoid 404 on "/send-message".
        if ($path === '' || $path === '/') {
            return $baseUrl . '/api';
        }

        return $baseUrl;
    }

    private function headers(bool $isV2): array
    {
        $auth = $this->authorizationValue();

        $headers = [
            'Authorization' => $auth,
            'Accept' => 'application/json',
        ];

        if ($isV2) {
            $headers['Content-Type'] = 'application/json';
        }

        return [
            ...$headers,
        ];
    }

    private function authorizationValue(): string
    {
        $token = trim((string) $this->setting('token', config('whatsapp.drivers.wablas.token')));
        $secretKey = trim((string) $this->setting('secret_key', config('whatsapp.drivers.wablas.secret_key')));

        if ($token === '') {
            return '';
        }

        return $secretKey !== '' ? ($token . '.' . $secretKey) : $token;
    }

    private function authorizationMeta(): array
    {
        $token = trim((string) $this->setting('token', config('whatsapp.drivers.wablas.token')));
        $secretKey = trim((string) $this->setting('secret_key', config('whatsapp.drivers.wablas.secret_key')));
        $auth = $this->authorizationValue();

        return [
            'token_present' => $token !== '',
            'secret_key_present' => $secretKey !== '',
            'token_length' => strlen($token),
            'secret_key_length' => strlen($secretKey),
            'auth_has_dot' => str_contains($auth, '.'),
            'auth_length' => strlen($auth),
        ];
    }

    private function isV2Path(string $pathKey): bool
    {
        $path = (string) config("whatsapp.drivers.wablas.{$pathKey}");

        return str_contains($path, '/v2/');
    }

    private function timeout(): int
    {
        return (int) $this->setting('timeout', config('whatsapp.drivers.wablas.timeout', 15));
    }

    private function setting(string $key, mixed $default = null): mixed
    {
        $stored = Cache::get('settings:whatsapp', []);

        return $stored[$key] ?? $default;
    }

    private function decodeBody(string $rawBody): mixed
    {
        $decoded = json_decode($rawBody, true);

        return $decoded ?? $rawBody;
    }
}
