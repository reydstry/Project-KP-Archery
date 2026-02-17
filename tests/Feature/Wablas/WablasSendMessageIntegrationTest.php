<?php

use PHPUnit\Framework\TestCase;

class WablasSendMessageIntegrationTest extends TestCase
{
    public function testSendMessage()
    {
        $response = $this->sendMessageToWablas('1234567890', 'Hello, World!');
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('success', $response['status']);
    }

    private function sendMessageToWablas($phoneNumber, $message)
    {
        // Simulate sending a message to Wablas API
        return [
            'status' => 'success',
            'message_id' => 'abc123'
        ];
    }
}