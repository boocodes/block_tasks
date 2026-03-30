<?php 

namespace Tests3\Feature;

use Final3\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests3\TestCase;

class AuthorizeTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/projects');
        $response->assertStatus(200);
    }
}