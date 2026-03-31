<?php

namespace Final5\App\Listeners;

use Final5\App\Events\TaskStatusChangedEvent;
use Final5\App\Jobs\WebhookJob;
use Final5\App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Final5\App\Models\AuditLogs;
use Final5\App\Models\Project;
use Final5\App\Models\Webhook;
use Final5\App\Models\WebhookAttempts;
use Illuminate\Support\Facades\Log;
use Final5\App\Events\TaskCompletedEvent;

use function PHPUnit\Framework\isEmpty;

class WebhookListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */

    public function handleCompletedTask(TaskCompletedEvent $event): void
    {
        if ($event->idempotencyKey === null) return;
        $this->handleBoth($event->task, $event->idempotencyKey, $event->event);
    }
    public function handleStatusChanged(TaskStatusChangedEvent $event): void
    {
        if ($event->idempotencyKey === null) return;
        $this->handleBoth($event->task, $event->idempotencyKey, $event->event);
    }


    public function handleBoth(Task $task, string $idempotencyKey, string $event): void
    {
        $project = Project::where('id', $task->project_id)->first();
        if(!$project)
        {
            Log::info('No project founded by edited task', [
                'task_id' => $task->id,
                'event' => $event,
                'idempotency_key' => $idempotencyKey,
            ]);
            return;
        }
        $webhooksList = Webhook::where('project_id', $project->id)->where('enable', true)->orderBy('id')->get();
        if(!$webhooksList)
        {
            Log::info('No specified webhooks for current project', [
                'project_id' => $project->id,
                'event' => $event,
                'idempotency_key' => $idempotencyKey,
            ]);
            return;
        }

        foreach($webhooksList as $webhook)
        {
            $attempt = WebhookAttempts::where('webhook_id', $webhook->id)
                ->where('idempotency_key', $idempotencyKey)->first();
            if($attempt)
            {
                Log::info('Webhook attempt with current idempotency key was already processed', [
                    'webhook_id' => $webhook->id,
                    'idempotency_key' => $idempotencyKey,
                ]);
                continue;
            }
            WebhookJob::dispatch($webhook, $task, $idempotencyKey, $event);
        }
    }
}
