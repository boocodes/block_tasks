<?php

namespace Task6\App\Listeners;

use Task6\App\Events\TaskCompletedEvent;
use Task6\App\Models\TaskAudit;
use Task6\App\Jobs\SendTaskCompletedNotification;

class WriteTaskAuditLogListener
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
        $previousStatus = $event->previousStatus;
        $task = $event->task;
        $auditData = [
            'task_id' => $task['id'],
            'event' => 'completed',
            'occurred_at' => new \DateTimeImmutable()->format('c'),
            'meta' => json_encode([
                'author' => $task['user_id'],
                'previous_status' => $previousStatus,
            ])
        ];
        $result = TaskAudit::create($auditData);
        SendTaskCompletedNotification::dispatch($result->toArray());
    }
}
