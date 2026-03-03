<?php

namespace Task6\App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Task6\App\Events\TaskCompletedEvent;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Task6\App\Models\TaskAudit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Context;


class SendTaskCompletedNotification implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;
    /**
     * Create a new job instance.
     */
    public array $audit;
    public function __construct(array $audit)
    {
        $this->audit = $audit;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Task audit created. Id: {id}, occurred at: {occurred_at}', ['id' => $this->audit['id'], 'occurred_at' => $this->audit['occurred_at']]);
    }
}
