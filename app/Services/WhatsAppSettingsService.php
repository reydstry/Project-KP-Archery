<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class WhatsAppSettingsService
{
    private const WHATSAPP_SETTINGS_KEY = 'settings:whatsapp';
    private const REMINDER_SETTINGS_KEY = 'settings:reminder';

    public function getWhatsAppSettings(): array
    {
        $stored = Cache::get(self::WHATSAPP_SETTINGS_KEY, []);

        return [
            'driver' => $stored['driver'] ?? config('whatsapp.driver', 'dummy'),
            'base_url' => $stored['base_url'] ?? config('whatsapp.drivers.wablas.base_url'),
            'token' => $stored['token'] ?? config('whatsapp.drivers.wablas.token'),
            'secret_key' => $stored['secret_key'] ?? config('whatsapp.drivers.wablas.secret_key'),
            'timeout' => (int) ($stored['timeout'] ?? config('whatsapp.drivers.wablas.timeout', 15)),
            'sandbox' => (bool) ($stored['sandbox'] ?? false),
        ];
    }

    public function saveWhatsAppSettings(array $settings): array
    {
        $baseUrl = $this->normalizeWablasBaseUrl($settings['base_url'] ?? null);

        Cache::forever(self::WHATSAPP_SETTINGS_KEY, [
            'driver' => $settings['driver'],
            'base_url' => $baseUrl ?? config('whatsapp.drivers.wablas.base_url'),
            'token' => $settings['token'] ?? '',
            'secret_key' => $settings['secret_key'] ?? '',
            'timeout' => (int) ($settings['timeout'] ?? 15),
            'sandbox' => (bool) ($settings['sandbox'] ?? false),
        ]);

        return $this->getWhatsAppSettings();
    }

    public function getReminderSettings(): array
    {
        $stored = Cache::get(self::REMINDER_SETTINGS_KEY, []);

        return [
            'enabled' => array_key_exists('enabled', $stored) ? (bool) $stored['enabled'] : true,
            'days_before_expired' => (int) ($stored['days_before_expired'] ?? 7),
            'cron' => '* * * * * php artisan schedule:run',
        ];
    }

    public function saveReminderSettings(array $settings): array
    {
        Cache::forever(self::REMINDER_SETTINGS_KEY, [
            'enabled' => (bool) ($settings['enabled'] ?? true),
            'days_before_expired' => (int) ($settings['days_before_expired'] ?? 7),
        ]);

        return $this->getReminderSettings();
    }

    private function normalizeWablasBaseUrl(?string $baseUrl): ?string
    {
        if ($baseUrl === null) {
            return null;
        }

        $baseUrl = rtrim(trim($baseUrl), '/');

        if ($baseUrl === '') {
            return null;
        }

        $parts = parse_url($baseUrl);

        if ($parts === false) {
            return $baseUrl;
        }

        $path = $parts['path'] ?? '';

        return ($path === '' || $path === '/')
            ? ($baseUrl . '/api')
            : $baseUrl;
    }
}
