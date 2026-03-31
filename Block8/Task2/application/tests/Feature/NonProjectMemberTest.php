<?php

namespace Tests\Feature;

use Final2\App\Enums\Priority;
use Final2\App\Enums\TaskStatus;
use Final2\App\Models\Project;
use Final2\App\Models\Task;
use Final2\App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NonProjectMemberTest extends TestCase
{
    public function testMain()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();


        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'test'
        ]);
        $project2 = Project::factory()->create([
            'owner_id' => $user2->id,
            'name' => 'test'
        ]);

        Sanctum::actingAs($user);

        $task = Task::factory()->create([
            'title' => 'test',
            'description' => 'description',
            'project_id' => $project->id,
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'due_date' => new \DateTimeImmutable()->format('c'),
        ]);

        Sanctum::actingAs($user2);

        $response = $this->getJson('/api/tasks/' . $task->id);

        $response->assertStatus(403);
    }
}
