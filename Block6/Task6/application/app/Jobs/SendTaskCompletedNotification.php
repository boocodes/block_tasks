<?php

namespace Task6\App\Jobs;

use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;
use Throwable;

class SendTaskCompletedNotification implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    /**
     * Create a new job instance.
     */

    private const MAX_NOTIFICATIONS_PER_SECOND = 5;
    public int $tries = 3;
    public $backoff = [1, 2, 5];
    public int $userId;
    public int $taskId;

    private static array $requestTimestamps = [];

    private const IDEMPOTENCY_ALIVE = 86400;
    public function __construct(int $userId, int $taskId)
    {
        $this->userId = $userId;
        $this->taskId = $taskId;
    }
    public function backoff(): array
    {
        return $this->backoff;
    }
    private function checkIfAlreadyProcessed(): bool
    {
        $key = "notification:" . $this->taskId;
        return Redis::exists($key);
    }
    private function markAlreadyProcessed(): void
    {
        $key = "notification:" . $this->taskId;
        Redis::setex($key, self::IDEMPOTENCY_ALIVE, 'processed');
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
        if ($this->checkIfAlreadyProcessed()) {
            return;
        }
        $this->applyRateLimit();
        try {
            if ($this->taskId % 14 === 0) {
                throw new \Exception('Divided by five');
            }
            $notificationData = [
                'userId' => $this->userId,
                'taskId' => $this->taskId,
                'occuredAt' => new DateTimeImmutable()->format('c'),
                'channel' => 'email'
            ];
            file_put_contents(storage_path('logs/notifications.log'), json_encode($notificationData), FILE_APPEND);
            $this->markAlreadyProcessed();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function applyRateLimit()
    {
        $currentTime = microtime(true);
        self::$requestTimestamps = array_filter(
            self::$requestTimestamps,
            function ($timestamp) use ($currentTime) {
                return ($currentTime - $timestamp) < 1;
            }
        );

        while (count(self::$requestTimestamps) >= self::MAX_NOTIFICATIONS_PER_SECOND) {
            $oldestTimestamp = min(self::$requestTimestamps);
            $nextAvailableTime = $oldestTimestamp + 1;

            if ($nextAvailableTime > $currentTime) {
                $sleepTime = ($nextAvailableTime - $currentTime) * 1000000;
                usleep((int) $sleepTime);
                $currentTime = microtime(true);

                self::$requestTimestamps = array_filter(
                    self::$requestTimestamps,
                    function ($timestamp) use ($currentTime) {
                        return ($currentTime - $timestamp) < 1;
                    }
                );

            } else {
                break;
            }
        }
        self::$requestTimestamps[] = microtime(true);

    }
}
