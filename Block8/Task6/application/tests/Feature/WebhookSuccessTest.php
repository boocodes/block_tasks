<?php


namespace Tests6\Feature;

use Final6\App\Enums\Priority;
use Final6\App\Enums\TaskStatus;
use Final6\App\Models\Project;
use Final6\App\Models\Task;
use Final6\App\Models\User;
use Final6\App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests6\TestCase;


class WebhookSuccessTest extends TestCase
{
    use RefreshDatabase;
    protected string $webhookUrl = "http://127.0.0.1:8001";

    public function testMain()
    {
        Http::fake([
            $this->webhookUrl => Http::response(['status' => 'ok'], 200)
        ]);

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'test'
        ]);

        $webhook = Webhook::create([
            'project_id' => $project->id,
            'url' => $this->webhookUrl,
            'secret' => 'test-secret',
            'enable' => true,
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'title' => 'Test'
        ]);

        $response = $this->patchJson('/api/tasks/' . $task->id, [
            'status' => TaskStatus::DONE->value,
            'title' => $task->title,
        ]);

        $response->assertStatus(200);

        Http::assertSent(function ($request) use ($webhook) {
            return $request->url() === $webhook->url;
        });

        $this->assertDatabaseHas('webhook_attempts', [
            'webhook_id' => $webhook->id,
            'event_type' => 'Completed',
            'status' => 'success',
            'http_code' => 200
        ]);
    }
}
