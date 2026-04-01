<?php

namespace Final7\App\Jobs;

use Exception;
use Final7\App\Models\ProcessedJobs;
use Final7\App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class TaskCompletedNotifyJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    public Task $task;
    public $userId;
    public string $idempotencyKey;
    public $tries = 3;

    /**
     * Create a new job instance.
     */

    public function __construct(Task $task, $userId, string $idempotencyKey)
    {
        $this->task = $task;
        $this->userId = $userId;
        $this->idempotencyKey = $idempotencyKey;
    }

    private function checkIsAlreadyProcessed(): bool
    {
        $key = "processed_job:{$this->idempotencyKey}";
        return Redis::exists($key);
    }

    public function markAsProcessed(): void
    {
        $key = "processed_jobs:{$this->idempotencyKey}";
        $data = [
            'idempotency_key' => $this->idempotencyKey,
            'task_id' => $this->task->id,
            'user_id' => $this->userId,
            'processed_at' => new \DateTimeImmutable()->format('c')
        ];
        Redis::setex($key, 86400, json_encode($data));
    }

    private function saveNotification(): void
    {
        $logPath = storage_path('/var/notifications.log');
        $logDir = dirname($logPath);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logData = [
            'occured_at' => new \DateTimeImmutable()->format('c'),
            'message' => 'Task completed',
            'idempotency_key' => $this->idempotencyKey,
            'user_id' => $this->userId,
            'task_id' => $this->task->id,
        ];
        $logLine = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
        file_put_contents($logPath, $logLine, FILE_APPEND | LOCK_EX);
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->checkIsAlreadyProcessed()) {
                Log::info(
                    'Task compled notify job already processed',
                    [
                        'idempotency_key' => $this->idempotencyKey,
                        'task_id' => $this->task->id,
                        'timestamp' => new \DateTimeImmutable()->format('c')
                    ]
                );
                return;
            }
            $this->markAsProcessed();
            Log::info(
                'Task completed notify job processed',
                [
                    'idempotency_key' => $this->idempotencyKey,
                    'task_id' => $this->task->id,
                    'timestamp' => new \DateTimeImmutable()->format('c'),
                ]
            );
            $this->saveNotification();
        } catch (\Exception $exception) {
            Log::error('Error at Task completed notify job', [
                'idempotency_key' => $this->idempotencyKey,
                'user_id' => $this->userId,
                'error' => $exception->getMessage(),
            ]);
            throw $exception;
        }
    }

    public function backoff(): array
    {
        return [1, 3, 5];
    }
    public function failed(\Throwable $exception): void
    {
        Log::error('Task completed notify job failed after ' . $this->tries . ' tries', [
            'task_id' => $this->task->id,
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
            'attempts' => $this->tries,
        ]);
        $key = "failed_jobs:{$this->idempotencyKey}";
        Redis::setex($key, 86400, json_encode([
            'idempotency_key' => $this->idempotencyKey,
            'task_id' => $this->task->id,
            'user_id' => $this->userId,
            'failed_at' => new \DateTimeImmutable()->format('c'),
        ]));
    }
}
