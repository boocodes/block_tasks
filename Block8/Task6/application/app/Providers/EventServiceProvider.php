<?php

namespace Final6\App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as RootProvider;

use Final6\App\Events\TaskCompletedEvent;
use Final6\App\Events\TaskCreatedEvent;
use Final6\App\Events\TaskStatusChangedEvent;

use Final6\App\Listeners\TaskCompletedListener;
use Final6\App\Listeners\TaskCreatedListener;
use Final6\App\Listeners\TaskStatusChangedListener;

use Final6\App\Listeners\WebhookListener;

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
