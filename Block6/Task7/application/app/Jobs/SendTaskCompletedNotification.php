<?php

namespace Task7\App\Jobs;

use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;
use Task7\App\Models\WebhookAttempts;
use Illuminate\Support\Facades\Http;
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

    private function logAttempt(int $attemptNumber, ?int $statusCode = null, ?string $response = null, string $status = 'failed'): void
    {
        WebhookAttempts::create([
            'taskId' => $this->taskId,
            'attempt_number' => $attemptNumber,
            'status_code' => $statusCode,
            'response' => $response,
            'status' => $status
        ]);

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

        $attemptNumber = $this->attempts() + 1;



        try {
            if ($this->taskId % 14 === 0) {
                throw new \Exception('Divided by five');
            }

            $response = Http::timeout(5)
                ->withHeaders([
                    'Idempotency-Key' => (string) $this->taskId
                ])
                ->post(
                    config('webhook.url'),
                    [
                        'taskId' => $this->taskId,
                        'status' => 'completed',
                        'occurredAt' => now(),
                    ]
                );
            $this->logAttempt(
                $attemptNumber,
                $response->status(),
                substr($response->body(), 0, 255),
                'success'
            );
            if ($response->successful()) {
                $this->markAlreadyProcessed();
            } else {
                throw new Exception($response->status());
            }
        } catch (Exception $exception) {
            if (!isset($response)) {
                $this->logAttempt(
                    $attemptNumber,
                    null,
                    $exception->getMessage(),
                    'failed'
                );
            }

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
