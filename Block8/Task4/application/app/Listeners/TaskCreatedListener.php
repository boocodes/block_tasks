<?php

namespace Final4\App\Listeners;

use Final4\App\Events\TaskCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Final4\App\Models\AuditLogs;
use Final4\App\Models\Task;

class TaskCreatedListener
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
    public function handle(TaskCreatedEvent $event): void
    {
        $auditLogInstance = new AuditLogs();
        $auditLogInstance->create([
            'occured_at' => new \DateTimeImmutable()->format('c'),
            'entity_type' => Task::class,
            'entity_id' => $event->task->id,
            'action' => 'Created',
            'meta' => json_encode([
                'user_id' => $event->userId,
            ])
        ]);
    }
}
