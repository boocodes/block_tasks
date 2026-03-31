<?php 

namespace Tests5\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests5\TestCase;

class NonauthorizedTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $response = $this->getJson('/api/projects');
        $response->assertStatus(401);
    }
}