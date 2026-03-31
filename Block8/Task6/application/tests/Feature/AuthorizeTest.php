<?php 

namespace Tests6\Feature;

use Final6\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests6\TestCase;

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