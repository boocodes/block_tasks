<?php

namespace Task3\App\Jobs;

use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class SendTaskCompletedNotification implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public int $tries = 3;
    public $backoff = [1, 2, 5];
    public int $userId;
    public int $taskId;
    public function __construct(int $userId, int $taskId)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
    }
    public function backoff(): array
    {
        return $this->backoff;
    }

    public function failed(Throwable $exception): void
    {
        $failedData = [
            'errorReason' => $exception->getMessage(),
            'occuredAt' => new DateTimeImmutable()->format('c'),
            'taskId' => $this->taskId,
        ];
        file_put_contents(storage_path('logs/failed_notifications.log'), json_encode($failedData), FILE_APPEND);
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {

        if ($this->taskId % 5 === 0) {
            throw new \Exception('Divided by five');
        }
        $notificationData = [
            'userId' => $this->userId,
            'taskId' => $this->taskId,
            'occuredAt' => new DateTimeImmutable()->format('c'),
            'channel' => 'email'
        ];
        file_put_contents(storage_path('logs/notifications.log'), json_encode($notificationData), FILE_APPEND);
    }
}
