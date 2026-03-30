<?php 

namespace Tests\Feature;

use Final2\App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthorizeTest extends TestCase
{
    public function testMain()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/projects');
        $response->assertStatus(200);
    }
}