<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

class OrderRefunded extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-repeat';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'amber';

    /**
     * Keep the refund, its order's number and the amount refunded.
     */
    public function data(mixed $refund): array
    {
        return [
            'refund_id' => $refund->id,
            'order_id' => $refund->order_id,
            'increment_id' => $refund->order?->increment_id,
            'base_grand_total' => (float) $refund->base_grand_total,
            'base_currency_code' => $refund->base_currency_code,
        ];
    }

    /**
     * The headline of the notification.
     */
    public function title(array $data): string
    {
        return trans('admin::app.notifications.types.order-refunded.title', ['order' => $data['increment_id'] ?? '']);
    }

    /**
     * The amount refunded.
     */
    public function message(array $data): ?string
    {
        return trans('admin::app.notifications.types.order-refunded.message', [
            'amount' => core()->formatPrice($data['base_grand_total'] ?? 0, $data['base_currency_code'] ?? null),
        ]);
    }

    /**
     * The refund's page.
     */
    public function url(array $data): ?string
    {
        return route('admin.sales.refunds.view', $data['refund_id']);
    }
}
