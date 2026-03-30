<?php 

namespace Tests\Feature;

use Final2\App\Models\User;
use Tests\TestCase;

class NonauthorizedTest extends TestCase
{
    public function testMain()
    {
        $response = $this->getJson('/api/projects');
        $response->assertStatus(401);
    }
}