<?php

namespace Final4\App\Listeners;

use Final4\App\Events\TaskCompletedEvent;
use Final4\App\Jobs\TaskCompletedNotifyJob;
use Final4\App\Models\AuditLogs;
use Final4\App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TaskCompletedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCompletedEvent $event): void
    {
        $auditLogInstance = new AuditLogs();
        $auditLogInstance->create([
            'occured_at' => new \DateTimeImmutable()->format('c'),
            'entity_type' => Task::class,
            'entity_id' => $event->task->id,
            'action' => 'Completed',
            'meta' => json_encode([
                'user_id' => $event->userId,
                'idempotency_key' => $event->idempotencyKey
            ])
        ]);
        TaskCompletedNotifyJob::dispatch($event->task, $event->userId, $event->idempotencyKey)->onQueue('Tasks');
    }
}
