<?php

namespace Final4\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as RootProvider;

use Final4\App\Events\TaskCompletedEvent;
use Final4\App\Events\TaskCreatedEvent;
use Final4\App\Events\TaskStatusChangedEvent;

use Final4\App\Listeners\TaskCompletedListener;
use Final4\App\Listeners\TaskCreatedListener;
use Final4\App\Listeners\TaskStatusChangedListener;


class EventServiceProvider extends RootProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    protected $listen = [
        TaskCompletedEvent::class => [
            TaskCompletedListener::class
        ],
        TaskCreatedEvent::class => [
            TaskCreatedListener::class
        ],
        TaskStatusChangedEvent::class => [
            TaskStatusChangedListener::class
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
