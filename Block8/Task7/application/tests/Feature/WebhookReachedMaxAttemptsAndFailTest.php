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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Sleep;
use Laravel\Sanctum\Sanctum;
use Tests7\TestCase;

class WebhookReachedMaxAttemptsAndFailTest extends TestCase
{
    use RefreshDatabase;
    protected string $webhookUrl = "http://webhook-receiver:81?die=true";

    public function testMain()
    {
        config(['queue.default' => 'redis']);

        \Illuminate\Support\Facades\Redis::flushdb();


        $user = User::factory()->create();
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

        WebhookJob::dispatch(
            $webhook,
            $task,
            '11010101',
            'Completed',
        );

        $this->artisan('queue:work', [
            '--once' => false,
            '--stop-when-empty' => true,
            '--max-time' => 10
        ]);



        $this->artisan('queue:work', ['--once' => true]);
        $this->artisan('queue:work', ['--once' => true]);
        $this->artisan('queue:work', ['--once' => true]);
        Sleep::sleep(4);

        $attempts = WebhookAttempts::where('webhook_id', $webhook->id)
            ->first();
        dump($attempts);
        $this->assertTrue($attempts !== null);
        $this->assertTrue($attempts->attempt === $attempts->max_attempts);
    }
}
