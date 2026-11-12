<?php

namespace Webkul\Admin\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public int $id,
        public string $type
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('admin.notifications');
    }

    /**
     * Broadcast nothing about the notification, so an admin learns of it only from a feed filtered by their role.
     */
    public function broadcastWith(): array
    {
        return [];
    }

    /**
     * Broadcast on its own queue, worked with `php artisan queue:work --queue=broadcastable`.
     */
    public function broadcastQueue(): string
    {
        return 'broadcastable';
    }

    /**
     * Get the name the event should broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'notification-created';
    }
}
