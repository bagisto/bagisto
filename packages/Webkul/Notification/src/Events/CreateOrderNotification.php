<?php

namespace Webkul\Notification\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreateOrderNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {    

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


    public function broadcastQueue()
    {
        return 'broadcastable';
    }

    /**
     * Get the channels the event should broadcast as.
     *
     * @return broadcast name
     */
    public function broadcastAs()
    {
        return 'create-notification';
    }
}
