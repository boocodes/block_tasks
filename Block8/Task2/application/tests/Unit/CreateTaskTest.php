<?php

namespace Tests\Unit;

use Final2\App\Enums\Priority;
use Final2\App\Enums\TaskStatus;
use Final2\App\Http\Requests\Task\CreateRequest;
use Final2\App\Services\TaskService;
use DateTimeImmutable;
use Final2\App\Models\Project;
use Final2\App\Models\User;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

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
