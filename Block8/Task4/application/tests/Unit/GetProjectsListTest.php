<?php

namespace Tests4\Feature;

use Final4\App\Models\Project;
use Final4\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests4\TestCase;
use Laravel\Sanctum\Sanctum;

class GetProjectsListTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $user->id . '',
            'name' => 'test'
        ]);
        
        $project = Project::factory()->create([
            'owner_id' => $user->id . '',
            'name' => 'test'
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/projects');
        $response->assertStatus(200);
        $this->assertIsArray($response->json('data'));
    }
}
