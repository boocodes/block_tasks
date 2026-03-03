<?php

namespace Task6\App\Listeners;

use Task6\App\Events\TaskCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Task6\App\Jobs\SendTaskCompletedNotification;
use Task6\App\Models\TaskAudit;

class WriteTaskAuditLogListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCompletedEvent $event): void
    {
        SendTaskCompletedNotification::dispatch($event);
    }
}
