<?php

namespace Tests\Unit\Services;

use App\Contracts\WhatsAppGatewayInterface;
use App\Gateways\DummyGateway;
use App\Models\WaLog;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_message_uses_dummy_gateway_and_logs_success(): void
    {
        $this->app->singleton(WhatsAppGatewayInterface::class, fn () => new DummyGateway());

        $service = $this->app->make(WhatsAppService::class);
        $response = $service->sendMessage('08123456789', 'Halo test');

        $this->assertTrue($response['success']);
        $this->assertDatabaseHas('wa_logs', [
            'phone' => '08123456789',
            'status' => 'success',
        ]);
    }

    public function test_send_bulk_logs_success_and_fail_results(): void
    {
        $fakeGateway = new class implements WhatsAppGatewayInterface {
            public function sendMessage(string $phone, string $message): array
            {
                return [
                    'phone' => $phone,
                    'message' => $message,
                    'success' => !str_ends_with($phone, '999'),
                    'body' => ['message' => 'single'],
                ];
            }

            public function sendBulk(array $phones, string $message): array
            {
                return array_map(function (string $phone) use ($message) {
                    $success = !str_ends_with($phone, '999');

                    return [
                        'phone' => $phone,
                        'message' => $message,
                        'success' => $success,
                        'body' => ['message' => $success ? 'ok' : 'error'],
                    ];
                }, $phones);
            }

            public function scheduleMessage(array $phones, string $message, \DateTimeInterface $sendAt): array
            {
                return [];
            }
        };

        $this->app->singleton(WhatsAppGatewayInterface::class, fn () => $fakeGateway);

        $service = $this->app->make(WhatsAppService::class);
        $responses = $service->sendBulk(['628111111111', '628111111999'], 'Bulk test');

        $this->assertCount(2, $responses);
        $this->assertSame(1, WaLog::query()->where('status', 'success')->count());
        $this->assertSame(1, WaLog::query()->where('status', 'failed')->count());
    }
}
