<?php

namespace App\Contracts;

interface WhatsAppGatewayInterface
{
    public function sendMessage(string $phone, string $message): array;

    public function sendBulk(array $phones, string $message): array;

    public function scheduleMessage(array $phones, string $message, \DateTimeInterface $sendAt): array;
}
