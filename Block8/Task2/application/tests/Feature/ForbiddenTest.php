<?php

namespace Tests\Feature;

use Final2\App\Models\Project;
use Final2\App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ForbiddenTest extends TestCase
{
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
