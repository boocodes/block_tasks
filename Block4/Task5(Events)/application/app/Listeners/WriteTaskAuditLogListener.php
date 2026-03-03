<?php

namespace Task5\App\Listeners;

use Task5\App\Events\TaskCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Task5\App\Models\Task;
use Task5\App\Enums\TaskStatus;
use Task5\App\Models\TaskAudit;

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
        TaskAudit::create($auditData);
    }
}
