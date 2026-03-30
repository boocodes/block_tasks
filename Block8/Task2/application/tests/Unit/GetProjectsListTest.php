<?php

namespace Tests\Unit;

use Final2\App\Http\Resources\Project\ProjectResource;
use Final2\App\Models\Project;
use Final2\App\Models\Task;
use Final2\App\Models\User;
use Final2\App\Repositories\ProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
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
