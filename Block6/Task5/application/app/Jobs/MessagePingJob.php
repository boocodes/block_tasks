<?php

namespace Task5\App\Jobs;

use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MessagePingJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;

    public int $userId;
    public int $taskId;
    /**
     * Create a new job instance.
     */
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
        $notificationData = [
            'userId' => $this->userId,
            'taskId' => $this->taskId,
            'occuredAt' => new DateTimeImmutable()->format('c')
        ];
        file_put_contents(storage_path('logs/notifications.log'), json_encode($notificationData), FILE_APPEND);
    }
}
