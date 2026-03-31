<?php

namespace Tests6\Feature;

use Final6\App\Models\Project;
use Final6\App\Models\Task;
use Final6\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests6\TestCase;
use Final6\App\Enums\TaskStatus;
use Final6\App\Enums\Priority;
use Laravel\Sanctum\Sanctum;

class GetTasksListTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $user->id . '',
            'name' => 'test'
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id . '',
            'title' => 'test',
            'description' => 'description',
            'status' => TaskStatus::DONE->value,
            'priority' => Priority::CRITICAL->value,
            'due_date' => new \DateTimeImmutable()->format('c')
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id . '',
            'title' => 'test2',
            'description' => 'description2',
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::LOW->value,
            'due_date' => new \DateTimeImmutable()->format('c')
        ]);

        Sanctum::actingAs($user);

        $resonse = $this->getJson('/api/tasks');

        $resonse->assertStatus(200);
        $this->assertIsArray($resonse->json('data'));
    }
}
