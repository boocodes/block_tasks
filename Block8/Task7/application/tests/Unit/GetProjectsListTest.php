<?php

namespace Tests7\Feature;

use Final7\App\Models\Project;
use Final7\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;
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
