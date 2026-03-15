<?php

namespace Task2\App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Task2\App\Events\TaskCompleted;
use Task2\App\Jobs\SendTaskCompletedNotification;

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
