<?php

namespace Task5\App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Task5\App\Events\TaskCompleted;
use Task5\App\Jobs\SendTaskCompletedNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Event::listen(
            TaskCompleted::class,
            function($event)
            {
                SendTaskCompletedNotification::dispatch($event->userId, $event->taskId);
            }
        );
    }
}
