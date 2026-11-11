<?php

namespace Webkul\Admin\Notifications\Types;

use Webkul\Notification\Types\AbstractType;

class GDPRRequested extends AbstractType
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-information';

    /**
     * The colour of the icon.
     */
    protected string $tone = 'gray';

    /**
     * Keep the request's kind and the customer who made it.
     */
    public function data(mixed $request): array
    {
        return [
            'request_id' => $request->id,
            'type' => $request->type,
            'customer_name' => trim(($request->customer?->first_name ?? '').' '.($request->customer?->last_name ?? '')),
            'email' => $request->email ?? $request->customer?->email,
        ];
    }

    /**
     * The headline of the notification, naming the kind of request.
     */
    public function title(array $data): string
    {
        return ($data['type'] ?? null) === 'delete'
            ? trans('admin::app.notifications.types.gdpr-requested.delete-title')
            : trans('admin::app.notifications.types.gdpr-requested.update-title');
    }

    /**
     * The customer who made the request.
     */
    public function message(array $data): ?string
    {
        return trans('admin::app.notifications.types.gdpr-requested.message', [
            'customer' => $data['customer_name'] ?: ($data['email'] ?? ''),
        ]);
    }

    /**
     * The GDPR requests listing, where the request is handled.
     */
    public function url(array $data): ?string
    {
        return route('admin.customers.gdpr.index');
    }
}
