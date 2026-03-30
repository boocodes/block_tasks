<?php

namespace Tests2\Unit;

use Final3\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests2\TestCase;
use Final3\App\Models\Task;
use Final3\App\Models\Project;
use Final3\App\Enums\TaskStatus;
use Final3\App\Enums\Priority;
use Final3\App\Models\Comment;

class GetCommentsListTest extends TestCase
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
            'title' => 'test',
            'project_id' => $project->id . '',
            'description' => 'test',
            'status' => TaskStatus::DONE->value,
            'priority' => Priority::CRITICAL->value,
            'due_date' => new \DateTimeImmutable()->format('c'),
        ]);
        $comment = Comment::factory()->create([
            'body' => 'test',
            'task_id' => $task->id . '',
            'user_id' => $user->id . ''
        ]);
        $comment = Comment::factory()->create([
            'body' => 'test2',
            'task_id' => $task->id . '',
            'user_id' => $user->id . ''
        ]);
            
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/comments/');

        $response->assertStatus(200);
        $this->assertIsArray($response->json('data'));
    }
}
