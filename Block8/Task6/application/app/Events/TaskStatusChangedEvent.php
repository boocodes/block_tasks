<?php

namespace Final6\App\Events;

use Final6\App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public $userId;
    public string $idempotencyKey;
    public string $previousStatus;
    public string $event = 'Changed status';

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, $previousStatus, $userId, $idempotencyKey)
    {
        $this->task = $task;
        $this->previousStatus = $previousStatus;
        $this->userId = $userId;
        $this->idempotencyKey = $idempotencyKey;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
