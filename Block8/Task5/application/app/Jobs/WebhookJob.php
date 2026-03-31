<?php

namespace Final5\App\Jobs;

use Final5\App\Models\Project;
use Final5\App\Models\Task;
use Final5\App\Models\Webhook;
use Final5\App\Models\WebhookAttempts;
use Final5\App\Models\WebhookProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookJob implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels, InteractsWithQueue;


    public Task $task;
    public string $idempotencyKey;
    public string $event;
    public Webhook $webhook;

    public $tries = 3;

    public function backoff(): array
    {
        return [1, 3, 5];
    }
    public function failed(\Throwable $exception): void {}
    /**
     * Create a new job instance.
     */
    public function __construct(Webhook $webhook, Task $task, string $idempotencyKey, string $event)
    {
        $this->webhook = $webhook;
        $this->task = $task;
        $this->idempotencyKey = $idempotencyKey;
        $this->event = $event;
    }

    private function generateSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->webhook->secret);
    }

    private function execHook(array $payloadData, WebhookAttempts $attempt, int $attemptCurrentCount)
    {
        $startTime = microtime(true);
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Webhook-Signature' => $this->generateSignature(json_encode($payloadData)),
                    'Idempotency-Key' => $this->idempotencyKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->webhook->url, $payloadData);

            $attempt->update([
                'status' => $response->successful() ? 'success' : 'failed',
                'attempt' => $attemptCurrentCount,
                'http_code' => $response->status(),
                'response_time' =>  (int)((microtime(true) - $startTime) * 1000),
                'executed_at' => new \DateTimeImmutable()->format('c'),
            ]);
        } catch (\Exception $exception) {
            $responseTimeMs = (int)((microtime(true) - $startTime) * 1000);
            $attempt->update([
                'status' => 'failed',
                'attempt' => $attemptCurrentCount,
                'response_time' => $responseTimeMs,
                'error' => $exception->getMessage(),
                'executed_at' => new \DateTimeImmutable()->format('c'),
            ]);
            throw $exception;
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payloadData = [
            'event' => $this->event,
            'timestamp' => new \DateTimeImmutable()->format('c'),
            'idempotency_key' => $this->idempotencyKey,
            'webhook_id' => $this->webhook->id,
            'project_id' => $this->webhook->project_id,
            'task_id' => $this->task->id,
        ];
        $attemptCurrentCount = 0;
        $attempt = WebhookAttempts::where('idempotency_key', $this->idempotencyKey)
            ->where('webhook_id', $this->webhook->id)->first();
        if ($attempt) {
            if ($attempt->status === 'success') {
                return;
            }
            $attemptCurrentCount = $attempt->attempt;
        } else {
            $attempt = WebhookAttempts::create([
                'attempt' => 1,
                'status' => 'pending',
                'idempotency_key' => $this->idempotencyKey,
                'event_type' => $this->event,
                'entity_id' => $this->task->id,
                'webhook_id' => $this->webhook->id,
                'max_attempts' => $this->tries,
                'scheduled_at' => new \DateTimeImmutable()->format('c')
            ]);
            $attemptCurrentCount = 1;
        }

        if ($attempt->attempt > $this->tries) {
            return;
        }

        $execHookResult = $this->execHook($payloadData, $attempt, $attemptCurrentCount);
    }
}
