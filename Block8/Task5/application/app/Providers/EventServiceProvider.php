<?php

namespace Final5\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as RootProvider;

use Final5\App\Events\TaskCompletedEvent;
use Final5\App\Events\TaskCreatedEvent;
use Final5\App\Events\TaskStatusChangedEvent;

use Final5\App\Listeners\TaskCompletedListener;
use Final5\App\Listeners\TaskCreatedListener;
use Final5\App\Listeners\TaskStatusChangedListener;

use Final5\App\Listeners\WebhookListener;

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
            TaskCompletedListener::class,
            [WebhookListener::class, 'handleCompletedTask']
        ],
        TaskCreatedEvent::class => [
            TaskCreatedListener::class
        ],
        TaskStatusChangedEvent::class => [
            TaskStatusChangedListener::class,
            [WebhookListener::class, 'handleStatusChanged']
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
