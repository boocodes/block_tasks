<?php


namespace Tests5\Feature;

use Final5\App\Enums\Priority;
use Final5\App\Enums\TaskStatus;
use Final5\App\Events\TaskCompletedEvent;
use Final5\App\Jobs\TaskCompletedNotifyJob;
use Final5\App\Models\Project;
use Final5\App\Models\Task;
use Final5\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests5\TestCase;



class TaskCompletedQueueTest extends TestCase
{
    use RefreshDatabase;


    public function testMain()
    {
        Queue::fake();

        $user = User::factory()->create();

        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'test'
        ]);

        $taskData = [
            'title' => 'test',
            'project_id' => $project->id,
            'description' => 'test',
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'due_date' => new \DateTimeImmutable()->format('c'),
        ];


        $task = Task::factory()->create($taskData);

        Sanctum::actingAs($user);

        $newTaskData = $taskData;
        $newTaskData['status'] = TaskStatus::DONE->value;

        $response = $this->patchJson('/api/tasks/' . $task->id, $newTaskData);

        $response->assertStatus(200);

        Queue::assertPushed(TaskCompletedNotifyJob::class);
    }
}