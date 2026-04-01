<?php

namespace Tests7\Feature;

use Final7\App\Enums\Priority;
use Final7\App\Enums\TaskStatus;
use Final7\App\Models\User;
use Tests7\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Final7\App\Models\Project;
use Final7\App\Models\Task;

class CreateCommentTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $user->id
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'test',
            'description' => 'description',
            'status' => TaskStatus::DONE->value,
            'priority' => Priority::CRITICAL->value,
            'due_date' => new \DateTimeImmutable()->format('c')
        ]);

        Sanctum::actingAs($user);

        $commentData = [
            'task_id' => $task->id . '',
            'body' => 'Test comment',
        ];

        $response = $this->postJson('/api/comments', $commentData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('comments', [
            'body' => $commentData['body'],
            'user_id' => $user->id,
            'task_id' => $task->id
        ]);
    }
}
