<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WhatsAppSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::forget('settings:whatsapp');
        Cache::forget('settings:reminder');
    }

    public function test_admin_can_get_and_update_whatsapp_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/whatsapp/settings')
            ->assertOk()
            ->assertJsonPath('data.driver', config('whatsapp.driver', 'dummy'));

        $payload = [
            'driver' => 'dummy',
            'base_url' => 'https://example.com',
            'token' => 'test-token',
            'secret_key' => 'test-secret',
            'timeout' => 20,
            'sandbox' => true,
        ];

        $this->actingAs($admin, 'sanctum')
            ->putJson('/api/admin/whatsapp/settings', $payload)
            ->assertOk()
            ->assertJsonPath('data.driver', 'dummy')
            ->assertJsonPath('data.base_url', 'https://example.com')
            ->assertJsonPath('data.timeout', 20)
            ->assertJsonPath('data.sandbox', true);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/whatsapp/settings')
            ->assertOk()
            ->assertJsonPath('data.token', 'test-token')
            ->assertJsonPath('data.secret_key', 'test-secret');
    }

    public function test_admin_can_get_and_update_reminder_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/whatsapp/reminder-settings')
            ->assertOk()
            ->assertJsonPath('data.enabled', true)
            ->assertJsonPath('data.days_before_expired', 7);

        $this->actingAs($admin, 'sanctum')
            ->putJson('/api/admin/whatsapp/reminder-settings', [
                'enabled' => false,
                'days_before_expired' => 10,
            ])
            ->assertOk()
            ->assertJsonPath('data.enabled', false)
            ->assertJsonPath('data.days_before_expired', 10);

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/whatsapp/reminder-settings')
            ->assertOk()
            ->assertJsonPath('data.enabled', false)
            ->assertJsonPath('data.days_before_expired', 10);
    }
}
