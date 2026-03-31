<?php

namespace Tests6\Feature;

use Final6\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests6\TestCase;

use Final6\App\Models\Project;
use Final6\App\Models\Task;

use Final6\App\Enums\TaskStatus;
use Final6\App\Enums\Priority;
use Final6\App\Models\Comment;

class GetCommentTest extends TestCase
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

        $comment = Comment::factory()->create([
            'task_id' => $task->id . '',
            'body' => 'Test',
            'user_id' => $user->id . ''
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/comments/' . $comment->id);

        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }
}
