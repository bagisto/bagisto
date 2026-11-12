<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

class CustomerRegistered extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-customer';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'green';

    /**
     * Keep the customer's name and email.
     */
    public function data(mixed $customer): array
    {
        return [
            'customer_id' => $customer->id,
            'name' => trim($customer->first_name.' '.$customer->last_name),
            'email' => $customer->email,
        ];
    }

    /**
     * The headline of the notification.
     */
    public function title(array $data): string
    {
        return trans('admin::app.notifications.types.customer-registered.title');
    }

    /**
     * The customer's name and email.
     */
    public function message(array $data): ?string
    {
        return trans('admin::app.notifications.types.customer-registered.message', [
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
        ]);
    }

    /**
     * The customer's page.
     */
    public function url(array $data): ?string
    {
        return route('admin.customers.customers.view', $data['customer_id']);
    }
}
