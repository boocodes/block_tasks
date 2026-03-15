<?php

namespace Task4\App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Task4\App\Events\TaskCompleted;
use Task4\App\Jobs\SendTaskCompletedNotification;

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
