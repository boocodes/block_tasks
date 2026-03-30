<?php 

namespace Tests2\Feature;

use Final3\App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests2\TestCase;

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