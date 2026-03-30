<?php 

namespace Tests2\Feature;

use Final3\App\Models\User;
use Tests2\TestCase;

class NonauthorizedTest extends TestCase
{
    public function testMain()
    {
        $response = $this->getJson('/api/projects');
        $response->assertStatus(401);
    }
}