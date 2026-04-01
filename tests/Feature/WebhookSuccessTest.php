<?php


namespace Tests7\Feature;

use Final7\App\Enums\Priority;
use Final7\App\Enums\TaskStatus;
use Final7\App\Jobs\WebhookJob;
use Final7\App\Models\Project;
use Final7\App\Models\Task;
use Final7\App\Models\User;
use Final7\App\Models\Webhook;
use Final7\App\Models\WebhookProcessed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests7\TestCase;


class WebhookSuccessTest extends TestCase
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
            'secret' => 'secret-test',
        ]);

        $task = Task::create([
            'title' => 'Test title',
            'description' => 'Test description',
            'project_id' => $project->id,
            'status' => TaskStatus::NEW->value,
            'priority' => Priority::NORMAL->value,
            'due_date' => '2025-12-31',
        ]);

        $payload = json_encode([
            'name' => 'test'
        ]);
        $signature = hash_hmac('sha256', $payload, $webhook->secret);
        $response = $this
            ->patchJson('/api/tasks/' . $task->id, [
                'status' => TaskStatus::DONE->value,
            ]);
        $response->assertStatus(200);
    

        $webhookSuccessProcessed = WebhookProcessed::where('webhook_id', $webhook->id)->first();

        $this->assertNotNull($webhookSuccessProcessed);
    }
}
