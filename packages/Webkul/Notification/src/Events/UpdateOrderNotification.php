<?php

namespace Webkul\Notification\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UpdateOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notification');
    }

     /**
     * Broadcast with data.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }

    public function broadcastQueue () {
        return 'broadcastable';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return broadcast name
     */
    public function broadcastAs () {
        return 'update-notification';
    }
}
