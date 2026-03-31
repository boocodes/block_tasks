<?php

namespace Final5\App\Jobs;

use Exception;
use Final5\App\Models\ProcessedJobs;
use Final5\App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return ProcessedJobs::where('idempotency_key', $this->idempotencyKey)->exists();
    }

    private function saveNotification(): void
    {
        $logPath = storage_path('var/notification.log');
        $logDir = dirname($logPath);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0775, true);
        }
        $logData = [
            'occured_at' => new \DateTimeImmutable()->format('c'),
            'message' => 'Task successfully completed',
            'idempotency_key' => $this->idempotencyKey,
            'user_id' => $this->userId,
        ];
        $logLine = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL;
        file_put_contents($logPath, $logLine, FILE_APPEND | LOCK_EX);
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //throw new \Exception('test');
        if ($this->checkIsAlreadyProcessed()) {
            return;
        } else {
            DB::transaction(function () {
                ProcessedJobs::create([
                    'idempotency_key' => $this->idempotencyKey,
                ]);
            });
            Log::info('completed!');
            $this->saveNotification();
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
    }
}
