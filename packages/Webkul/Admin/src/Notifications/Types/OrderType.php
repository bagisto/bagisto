<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

abstract class OrderType extends AbstractType
{
    /**
     * Keep the order's number, customer and total, so the notification reads the same once the order changes.
     */
    public function data(mixed $order): array
    {
        return [
            'order_id' => $order->id,
            'increment_id' => $order->increment_id,
            'customer_name' => trim($order->customer_first_name.' '.$order->customer_last_name),
            'base_grand_total' => (float) $order->base_grand_total,
            'base_currency_code' => $order->base_currency_code,
        ];
    }

    /**
     * The order's total and the customer who placed it.
     */
    public function message(array $data): ?string
    {
        return trans('admin::app.notifications.types.order.message', [
            'total' => core()->formatPrice($data['base_grand_total'] ?? 0, $data['base_currency_code'] ?? null),
            'customer' => $data['customer_name'] ?? '',
        ]);
    }

    /**
     * The order's page.
     */
    public function url(array $data): ?string
    {
        return route('admin.sales.orders.view', $data['order_id']);
    }
}
