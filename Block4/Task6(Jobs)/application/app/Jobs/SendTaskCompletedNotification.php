<?php

namespace Task6\App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Task6\App\Events\TaskCompletedEvent;
use Task6\App\Models\TaskAudit;
use Task6\App\Models\Task;
use Illuminate\Support\Facades\Log;

class SendTaskCompletedNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public TaskCompletedEvent $event;
    public function __construct(TaskCompletedEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $task = $this->event->audit;
        $previousStatus = $task->getOriginal('status')->value;
        $auditData = [
            'task_id' => $task['id'],
            'event' => 'completed',
            'occurred_at' => new \DateTimeImmutable()->format('c'),
            'meta' => [
                'author' => $task['user_id'],
                'previous_status' => $previousStatus,
            ]
        ];
        $result = TaskAudit::create($auditData);
        if(!$result) {return;}

        Log::info('Audit created, id - ', $result->id . ', occurred at - ' . $result->occurred_at);
    }
}
