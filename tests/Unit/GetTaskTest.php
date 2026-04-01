<?php

namespace Tests7\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests7\TestCase;
use Final7\App\Models\User;
use Final7\App\Models\Project;
use Final7\App\Models\Task;
use Laravel\Sanctum\Sanctum;
use Final7\App\Enums\TaskStatus;
use Final7\App\Enums\Priority;


class GetTaskTest extends TestCase
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

        Sanctum::actingAs($user);

        $resonse = $this->getJson('/api/tasks/' . $task->id);

        $resonse->assertStatus(200);
        $this->assertIsArray($resonse->json());
    }
}
