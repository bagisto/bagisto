<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

class ReturnRequested extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-repeat';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'purple';

    /**
     * Keep the return request and the order it is for.
     */
    public function data(mixed $rma): array
    {
        return [
            'rma_id' => $rma->id,
            'order_id' => $rma->order_id,
            'increment_id' => $rma->order?->increment_id,
            'customer_name' => trim(($rma->order?->customer_first_name ?? '').' '.($rma->order?->customer_last_name ?? '')),
        ];
    }

    /**
     * The headline of the notification.
     */
    public function title(array $data): string
    {
        return trans('admin::app.notifications.types.return-requested.title', ['id' => $data['rma_id'] ?? '']);
    }

    /**
     * The order the return is for, and its customer.
     */
    public function message(array $data): ?string
    {
        return trans('admin::app.notifications.types.return-requested.message', [
            'order' => $data['increment_id'] ?? '',
            'customer' => $data['customer_name'] ?? '',
        ]);
    }

    /**
     * The return request's page.
     */
    public function url(array $data): ?string
    {
        return route('admin.sales.rma.requests.view', $data['rma_id']);
    }
}
