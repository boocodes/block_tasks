<?php

namespace Task5\App\Listeners;

use Task5\App\Events\TaskCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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
        $task = $event->audit;
        $previousStatus = $task->getOriginal('status');

        $auditData = [
            'task_id' => $task['id'],
            'event' => 'completed',
            'occurred_at' => new \DateTimeImmutable()->format('c'),
            'meta' => [
                'author' => $task['user_id'],
                'previous_status' => $previousStatus,
            ]
        ];
        TaskAudit::create($auditData);
    }
}
