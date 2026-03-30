<?php 

namespace Tests4\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests4\TestCase;

class NonauthorizedTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $response = $this->getJson('/api/projects');
        $response->assertStatus(401);
    }
}