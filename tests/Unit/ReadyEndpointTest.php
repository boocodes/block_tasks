<?php

namespace Tests7\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;

class ReadyEndpointTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $response = $this->getJson('/api/ready');
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'ready',
                'database' => 'connected'
            ]);
    }
}
