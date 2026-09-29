<?php

namespace Webkul\Notification\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct(protected $data) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('admin.notifications');
    }

    /**
     * Broadcast with data, so a listener need not fetch the order to show it.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }

    /**
     * Separate queue.
     *
     * Command: `php artisan queue:work --queue=broadcastable`
     *
     * @return string
     */
    public function broadcastQueue()
    {
        return 'broadcastable';
    }

    /**
     * Get the name the event should broadcast as.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'update-notification';
    }
}
