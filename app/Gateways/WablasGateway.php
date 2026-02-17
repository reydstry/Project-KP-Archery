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
        $response = Http::timeout($this->timeout())
            ->withHeaders($this->headers())
            ->post($this->url('send_message_path'), [
                'phone' => $phone,
                'message' => $message,
            ]);

        return [
            'provider' => 'wablas',
            'phone' => $phone,
            'message' => $message,
            'success' => $response->successful(),
            'status_code' => $response->status(),
            'body' => $this->decodeBody($response->body()),
        ];
    }

    public function sendBulk(array $phones, string $message): array
    {
        $payload = [
            'data' => array_map(fn (string $phone) => [
                'phone' => $phone,
                'message' => $message,
            ], $phones),
        ];

        $response = Http::timeout($this->timeout())
            ->withHeaders($this->headers())
            ->post($this->url('send_bulk_path'), $payload);

        if ($response->successful()) {
            $body = $this->decodeBody($response->body());

            return array_map(fn (string $phone) => [
                'provider' => 'wablas',
                'phone' => $phone,
                'message' => $message,
                'success' => true,
                'status_code' => $response->status(),
                'body' => $body,
            ], $phones);
        }

        return array_map(fn (string $phone) => [
            'provider' => 'wablas',
            'phone' => $phone,
            'message' => $message,
            'success' => false,
            'status_code' => $response->status(),
            'body' => $this->decodeBody($response->body()),
        ], $phones);
    }

    public function scheduleMessage(array $phones, string $message, DateTimeInterface $sendAt): array
    {
        $payload = [
            'data' => array_map(fn (string $phone) => [
                'phone' => $phone,
                'message' => $message,
                'schedule' => $sendAt->format('Y-m-d H:i:s'),
            ], $phones),
        ];

        $response = Http::timeout($this->timeout())
            ->withHeaders($this->headers())
            ->post($this->url('schedule_path'), $payload);

        $body = $this->decodeBody($response->body());

        return array_map(fn (string $phone) => [
            'provider' => 'wablas',
            'phone' => $phone,
            'message' => $message,
            'success' => $response->successful(),
            'status_code' => $response->status(),
            'body' => $body,
        ], $phones);
    }

    private function url(string $pathKey): string
    {
        $baseUrl = rtrim((string) $this->setting('base_url', config('whatsapp.drivers.wablas.base_url')), '/');
        $path = '/' . ltrim((string) config("whatsapp.drivers.wablas.{$pathKey}"), '/');

        return $baseUrl . $path;
    }

    private function headers(): array
    {
        return [
            'Authorization' => (string) $this->setting('token', config('whatsapp.drivers.wablas.token')),
            'secret-key' => (string) $this->setting('secret_key', config('whatsapp.drivers.wablas.secret_key')),
            'Accept' => 'application/json',
        ];
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
