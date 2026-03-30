<?php 

namespace Tests3\Feature;

use Final3\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests3\TestCase;

class NonauthorizedTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $response = $this->getJson('/api/projects');
        $response->assertStatus(401);
    }
}