<?php

namespace Final6\App\Listeners;

use Final6\App\Events\TaskStatusChangedEvent;
use Final6\App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Final6\App\Models\AuditLogs;


class TaskStatusChangedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    
    }

    /**
     * Handle the event.
     */
    public function handle(TaskStatusChangedEvent $event): void
    {
        $auditLogInstance = new AuditLogs();
        $auditLogInstance->create([
            'occured_at' => new \DateTimeImmutable()->format('c'),
            'entity_type' => Task::class,
            'entity_id' => $event->task->id,
            'action' => 'Changed status',
            'meta' => json_encode([
                'user_id' => $event->userId,
                'previous_status' => $event->previousStatus,
                'current_status' => $event->task->status->value,
            ])
        ]);
    }
}
