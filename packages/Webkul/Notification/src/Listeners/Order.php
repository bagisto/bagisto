<?php

namespace Webkul\Notification\Listeners;

use Illuminate\Support\Facades\Log;
use Webkul\Notification\Events\CreateOrderNotification;
use Webkul\Notification\Events\UpdateOrderNotification;
use Webkul\Notification\Repositories\NotificationRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected NotificationRepository $notificationRepository) {}

    /**
     * Create a new resource.
     *
     * @return void
     */
    public function createOrder($order)
    {
        $this->notificationRepository->create(['type' => 'order', 'order_id' => $order->id]);

        $this->broadcast(new CreateOrderNotification([
            'id' => $order->id,
            'increment_id' => $order->increment_id,
            'status' => $order->status,
        ]));
    }

    /**
     * Fire an Event when the order status is updated.
     *
     * @return void
     */
    public function updateOrder($order)
    {
        $this->broadcast(new UpdateOrderNotification([
            'id' => $order->id,
            'status' => $order->status,
        ]));
    }

    /**
     * Broadcast a notification without letting an unreachable broadcaster fail the order.
     */
    protected function broadcast(object $event): void
    {
        try {
            event($event);
        } catch (\Exception $e) {
            Log::error('Notification Broadcast Failed: '.$e->getMessage());
        }
    }
}
