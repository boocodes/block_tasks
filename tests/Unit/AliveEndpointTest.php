<?php

namespace Tests7\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;

class AliveEndpointTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $response = $this->getJson('/api/health');
        $response->assertStatus(200);       
    }
}
