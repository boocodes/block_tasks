<?php

namespace Task2\App\Jobs;

use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class SendTaskCompletedNotification implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public int $userId;
    public int $taskId;
    public function __construct(int $userId, int $taskId)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo 'from job';
          $notificationData = [
            'userId' => $this->userId,
            'taskId' => $this->taskId,
            'occuredAt' => new DateTimeImmutable()->format('c'),
            'channel' => 'email'
        ];
        file_put_contents(storage_path('logs/notifications.log'), json_encode($notificationData), FILE_APPEND);
    }
}
