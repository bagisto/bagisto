<?php

namespace Webkul\Admin\Notifications\Types;

class OrderPlaced extends OrderType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-cart';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'blue';

    /**
     * The headline of the notification.
     */
    public function title(array $data): string
    {
        return trans('admin::app.notifications.types.order-placed.title', ['order' => $data['increment_id'] ?? '']);
    }
}
