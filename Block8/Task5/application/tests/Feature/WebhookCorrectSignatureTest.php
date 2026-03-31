<?php 

namespace Tests5\Feature;

use Final5\App\Enums\Priority;
use Final5\App\Enums\TaskStatus;
use Final5\App\Models\Project;
use Final5\App\Models\Task;
use Final5\App\Models\User;
use Final5\App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests5\TestCase;


class WebhookCorrectSignatureTest extends TestCase
{
    use RefreshDatabase;
    protected string $webhookUrl = "http://webhook-receiver:8080/webhook";


    public function testMain()
    {
        Http::fake([
            $this->webhookUrl => function ($request) 
            {
                $signature = $request->header('X-Webhook-Signature')[0];
                if(strlen($signature) === 64 && ctype_xdigit($signature))
                    {
                        return Http::response(['status' => 'ok'], 200);
                    } 
                return Http::response(['error' => 'Invalid signature'], 500);
            }
        ]);

        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $project = Project::factory()->create([
            'owner_id' => $user->id,
            'name' => 'test',
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
            'title' => 'test'
        ]);

        $response = $this->patchJson('/api/tasks/' . $task->id, [
            'status' => TaskStatus::DONE->value,
            'title' => $task->title,
        ]);

        $response->assertStatus(200);

        Http::assertSent(function ($request) {
            $signature = $request->header('X-Webhook-Signature')[0];
            return strlen($signature) === 64 && ctype_xdigit($signature);
        });

    }   
}