<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Sales\Models\Order;

class OrderCanceled extends OrderType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-cancel';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'red';

    /**
     * Notify only once the whole order is canceled, not when a return cancels one of its items.
     */
    public function shouldNotify(mixed $order): bool
    {
        return $order->status === Order::STATUS_CANCELED;
    }

    /**
     * The headline of the notification.
     */
    public function title(array $data): string
    {
        return trans('admin::app.notifications.types.order-canceled.title', ['order' => $data['increment_id'] ?? '']);
    }
}
