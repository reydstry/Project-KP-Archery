<?php

use PHPUnit\Framework\TestCase;

class WablasGatewayTest extends TestCase
{
    public function testCanSendMessage()
    {
        $response = $this->sendMessageToWablas('Hello, World!');
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Message sent successfully', $response['message']);
    }

    private function sendMessageToWablas($message)
    {
        // Simulate sending a message to Wablas API
        return [
            'status' => 200,
            'message' => 'Message sent successfully'
        ];
    }
}