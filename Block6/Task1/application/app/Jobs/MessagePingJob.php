<?php

namespace Task4\App\Jobs;

use DateTimeImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class MessagePingJob implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new job instance.
     */
    private int $messageOrder;
    public function __construct(int $messageOrder)
    {
        $this->messageOrder = $messageOrder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('{order}. Ping processed: {timestamp}', ['order' => $this->messageOrder, 'timestamp' => new DateTimeImmutable()->format('c')]);
    }
}
