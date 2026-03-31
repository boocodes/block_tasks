<?php 

namespace Tests6\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests6\TestCase;

class NonauthorizedTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $response = $this->getJson('/api/projects');
        $response->assertStatus(401);
    }
}