<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling(); // ✅ включаем для всех тестов класса
    }

    public function test_can_create_ticket(): void
    {
        $this->seed();
        $resp = $this->postJson('/api/tickets', [
            'name' => 'John',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'subject' => 'Help',
            'message' => 'Hello',
        ]);
        $resp->assertCreated()->assertJsonPath('data.subject','Help');
    }

    public function test_daily_limit(): void
    {
        $this->seed();
        $payload = [
            'name'=>'A','email'=>'a@ex.com','phone'=>'+1111111111',
            'subject'=>'S','message'=>'M'
        ];
        $this->postJson('/api/tickets', $payload)->assertCreated();
        $this->postJson('/api/tickets', $payload)->assertStatus(429);
    }
}
