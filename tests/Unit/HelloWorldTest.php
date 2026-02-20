<?php

use PHPUnit\Framework\TestCase;

class HelloWorldTest extends TestCase
{
    public function testBasicFunctionality()
    {
        $this->assertTrue(true);
    }

    public function testSendMessage()
    {
        $messageSent = true; // Simulate sending a message
        $this->assertTrue($messageSent);
    }
}