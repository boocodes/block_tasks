<?php

namespace Tests\Unit;

use Final2\App\Http\Resources\Project\ProjectResource;
use Final2\App\Models\Project;
use Final2\App\Models\User;
use Final2\App\Repositories\ProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetProjectTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $user->id . '',
            'name' => 'test'
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/projects/' . $project->id);
        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }
}
