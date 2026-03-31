<?php

namespace Tests5\Feature;

use Final5\App\Enums\Priority;
use Final5\App\Enums\TaskStatus;
use Final5\App\Models\AuditLogs;
use Final5\App\Models\Project;
use Final5\App\Models\Task;
use Final5\App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests5\TestCase;

class AuditTaskCompletedTest extends TestCase
{
    use RefreshDatabase;
    public function testMain()
    {
        $user = User::factory()->create();
        $auditLogInitialCount = AuditLogs::count();
        $project = Project::factory()->create([
            'owner_id' => $user->id . '',
            'name' => 'test'
        ]);
        Sanctum::actingAs($user);
        $taskData = [
            'title' => 'test',
            'project_id' => $project->id . '',
            'description' => 'test description',
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'due_date' => new \DateTimeImmutable()->format('c')
        ];
        $response = $this->postJson('/api/tasks', $taskData);
        $response->assertStatus(201);

        $auditLogAfterCreateCount = AuditLogs::count();

        $this->assertEquals($auditLogInitialCount + 1, $auditLogAfterCreateCount);

        $response->assertStatus(201);
        $taskFromDB = Task::where('title', $taskData['title'])
            ->where('project_id', $taskData['project_id'])
            ->where('description', $taskData['description'])
            ->where('status', $taskData['status'])
            ->where('priority', $taskData['priority'])
            ->where('due_date', $taskData['due_date'])
            ->first();
        $taskId = $taskFromDB->id;
        $newTaskData = $taskData;
        $newTaskData['status'] = TaskStatus::DONE->value;

        $response = $this->patchJson('/api/tasks/' . $taskId, $newTaskData);
        $response->assertStatus(200);

        $auditLogAfterUpdateCount = AuditLogs::count();

        $this->assertDatabaseHas('audit_logs', [
            'entity_type' => Task::class,
            'entity_id' => $taskId,
            'action' => 'Completed',
        ]);

        $this->assertEquals($auditLogAfterCreateCount + 1, $auditLogAfterUpdateCount);
    }
}
