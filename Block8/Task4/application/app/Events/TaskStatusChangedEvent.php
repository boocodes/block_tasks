<?php

namespace Final4\App\Events;

use Final4\App\Models\Task;
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
    public string $previousStatus;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, $previousStatus, $userId)
    {
        $this->task = $task;
        $this->previousStatus = $previousStatus;
        $this->userId = $userId;
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
