<?php


namespace Tests7\Feature;

use Final7\App\Enums\Priority;
use Final7\App\Enums\TaskStatus;
use Final7\App\Jobs\WebhookJob;
use Final7\App\Models\Project;
use Final7\App\Models\Task;
use Final7\App\Models\User;
use Final7\App\Models\Webhook;
use Final7\App\Models\WebhookAttempts;
use Final7\App\Models\WebhookProcessed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests7\TestCase;


class WebhookFailedTest extends TestCase
{
    use RefreshDatabase;
    protected string $webhookUrl = "http://webhook-receiver:81";

    public function testMain()
    {
        $user = new User()->factory()->create();

        Sanctum::actingAs($user);

        $project = Project::create([
            'name' => 'test project',
            'owner_id' => $user->id,
        ]);


        $webhook = Webhook::create([
            'project_id' => $project->id,
            'url' => $this->webhookUrl,
            'enable' => true,
            'secret' => 'WRONG-SECRET',
        ]);

        $task = Task::create([
            'title' => 'Test title',
            'description' => 'Test description',
            'project_id' => $project->id,
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'due_date' => '2025-12-31',
        ]);

        $response = $this
            ->patchJson('/api/tasks/' . $task->id, [
                'status' => TaskStatus::DONE->value,
            ]);
        $response->assertStatus(500);



        $webhookSuccessProcessed = WebhookProcessed::where('webhook_id', $webhook->id)->first();

        $this->assertNull($webhookSuccessProcessed);

        $webhookAttempts = WebhookAttempts::where('webhook_id', $webhook->id)->first();
        $this->assertNotNull($webhookAttempts);
    }
}
