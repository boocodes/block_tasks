<?php

namespace Tests5\Feature;

use Final5\App\Models\Project;
use Final5\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests5\TestCase;

class ForbiddenTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();


        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'test'
        ]);
        $project2 = Project::factory()->create([
            'owner_id' => $user2->id,
            'name' => 'test'
        ]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/projects/' . $project->id);
        $response->assertStatus(204);

        $response = $this->deleteJson('/api/projects/' . $project2->id);
        $response->assertStatus(403);
    }
}
