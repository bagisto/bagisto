<?php

namespace Webkul\Notification\Listeners;

use Illuminate\Database\Eloquent\Collection;
use Webkul\Notification\Repositories\NotificationRepository;
use Webkul\Notification\Events\CreateOrderNotification;
use Webkul\Notification\Events\UpdateOrderNotification;

class Order
{
    /**
     * NotificationRepository
     *
     * @var object
     */
    protected $notificationRepository;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Create a new resource.
     *
     * @return void
     */
    public function createOrder($order)
    {
        $this->notificationRepository->create(['type' => 'order', 'order_id' => $order->id]);
          
        event(new CreateOrderNotification);
    }

    /**
     * Fire an Event when the order status is updated.
     *
     * @return void
     */
    public function updateOrder($order)
    { 
        $orderArray = [
            'id'     => $order->id,
            'status' => $order->status,
        ];

        event(new UpdateOrderNotification($orderArray));
    }
}
