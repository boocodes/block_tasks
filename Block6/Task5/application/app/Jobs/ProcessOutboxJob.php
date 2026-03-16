<?php

namespace Task5\App\Jobs;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Task5\App\Enums\OutboxEventsStatus;
use Task5\App\Models\OutboxEvents;
use Illuminate\Support\Facades\Log;
use Throwable;
use Task5\App\Jobs\SendTaskCompletedNotification;

class ProcessOutboxJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue;

    /**
     * Create a new job instance.
     */

    public int $tries = 3;
    public array $backoff = [1, 3, 5];

    private int $maxAttempts = 3;

    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $events = OutboxEvents::where('status', OutboxEventsStatus::NEW)->where(function ($query) {
            $query->whereNull('available_at')
                ->orWhereRaw('available_at <= NOW()');
        })->orderBy('id')->get();



        if ($events->isEmpty()) {
            return;
        }
        $processed = 0;
        $failed = 0;
        foreach ($events as $event) {
            try {
                $this->processEvent($event);
                $processed++;
            } catch (\Throwable $e) {
                $failed++;
                $this->handleFailure($event, $e);
            }
        }

        $remaingCount = OutboxEvents::where('status', OutboxEventsStatus::NEW)->where(function ($query) {
            $query->whereNull('available_at')
                ->orWhereRaw(('available_at <= NOW()'));
        })->count();
        if ($remaingCount > 0) {
            static::dispatch()->onQueue('outbox');
        }


    }

    private function processEvent(OutboxEvents $event): void
    {

        if ($event->type === 'TaskCompleted') {
            $this->publishTaskCompleted($event);
            $event->status = OutboxEventsStatus::PUBLISHED;
            $event->save();
        }


    }
    private function publishTaskCompleted(OutboxEvents $event): void
    {
        $payload = $event->payload;

        if (!isset($payload['taskId']) || !isset($payload['userId'])) {
            throw new Exception('Invalid Task completed payload');
        }
        SendTaskCompletedNotification::dispatch(
            $payload['userId'],
            $payload['taskId'],
        )->onQueue('notifications');
    }
    private function handleFailure(OutboxEvents $event, \Throwable $e): void
    {
        $event->attempts++;
    
        $event->available_at = now()->addSeconds(pow(3, $event->attempts));
        $event->save();

        if ($event->attempts >= $this->maxAttempts) {
            $event->status = OutboxEventsStatus::FAILED;
            $event->save();
        }
    }
    public function failed(Throwable $e): void
    {
        Log::error('', ['error' => $e->getMessage(), 'occured_at' => now()]);
    }
}
