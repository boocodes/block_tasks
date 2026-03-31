<?php

namespace Tests5\Feature;

use Final5\App\Enums\Priority;
use Final5\App\Enums\TaskStatus;
use DateTimeImmutable;
use Final5\App\Models\Project;
use Final5\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests5\TestCase;

class CreateTaskTest extends TestCase
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
        $taskData = [
            'title' => 'test',
            'project_id' => $project->id . '',
            'description' => 'test',
            'status' => TaskStatus::DONE->value,
            'priority' => Priority::CRITICAL->value,
            'due_date' => new \DateTimeImmutable()->format('c'),
        ];
        $response = $this->postJson('/api/tasks', $taskData);
        $response->assertStatus(201);
    }
}
