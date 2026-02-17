<?php

namespace App\Gateways;

use App\Contracts\WhatsAppGatewayInterface;
use DateTimeInterface;

class DummyGateway implements WhatsAppGatewayInterface
{
    public function sendMessage(string $phone, string $message): array
    {
        $isSuccess = !str_contains(strtolower($phone), 'fail');

        return [
            'provider' => 'dummy',
            'phone' => $phone,
            'message' => $message,
            'success' => $isSuccess,
            'status_code' => $isSuccess ? 200 : 422,
            'body' => [
                'message' => $isSuccess ? 'Dummy message sent' : 'Dummy failed to send',
            ],
        ];
    }

    public function sendBulk(array $phones, string $message): array
    {
        return array_map(fn (string $phone) => $this->sendMessage($phone, $message), $phones);
    }

    public function scheduleMessage(array $phones, string $message, DateTimeInterface $sendAt): array
    {
        return array_map(function (string $phone) use ($message, $sendAt) {
            $isSuccess = !str_contains(strtolower($phone), 'fail');

            return [
                'provider' => 'dummy',
                'phone' => $phone,
                'message' => $message,
                'success' => $isSuccess,
                'status_code' => $isSuccess ? 200 : 422,
                'body' => [
                    'message' => $isSuccess ? 'Dummy message scheduled' : 'Dummy schedule failed',
                    'scheduled_at' => $sendAt->format(DateTimeInterface::ATOM),
                ],
            ];
        }, $phones);
    }
}
