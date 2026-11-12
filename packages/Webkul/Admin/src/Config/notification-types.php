<?php

use Webkul\Admin\Notifications\AdminNotifications;
use Webkul\Admin\Notifications\Types\CustomerRegistered;
use Webkul\Admin\Notifications\Types\GDPRRequested;
use Webkul\Admin\Notifications\Types\ImportCompleted;
use Webkul\Admin\Notifications\Types\OrderCanceled;
use Webkul\Admin\Notifications\Types\OrderPlaced;
use Webkul\Admin\Notifications\Types\OrderRefunded;
use Webkul\Admin\Notifications\Types\ReturnRequested;
use Webkul\Admin\Notifications\Types\ReviewCreated;

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Types
    |--------------------------------------------------------------------------
    |
    | The notification types the admin panel ships with.
    |
    */

    'order.placed' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'orders',
        'class' => OrderPlaced::class,
        'permission' => 'sales.orders',
        'events' => ['checkout.order.save.after'],
    ],

    'order.canceled' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'orders',
        'class' => OrderCanceled::class,
        'permission' => 'sales.orders',
        'events' => ['sales.order.cancel.after'],
    ],

    'order.refunded' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'orders',
        'class' => OrderRefunded::class,
        'permission' => 'sales.refunds',
        'events' => ['sales.refund.save.after'],
    ],

    'customer.registered' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'customers',
        'class' => CustomerRegistered::class,
        'permission' => 'customers.customers',
        'events' => ['customer.registration.after'],
    ],

    'review.created' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'reviews',
        'class' => ReviewCreated::class,
        'permission' => 'customers.reviews',
        'events' => ['customer.review.create.after'],
    ],

    'return.requested' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'returns',
        'class' => ReturnRequested::class,
        'permission' => 'sales.rma.requests',
        'events' => ['customer.rma.request.create.after'],
    ],

    'gdpr.requested' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'gdpr',
        'class' => GDPRRequested::class,
        'permission' => 'customers.gdpr_requests',
        'events' => ['customer.gdpr-request.create.after'],
    ],

    'import.completed' => [
        'audience' => AdminNotifications::AUDIENCE,
        'group' => 'imports',
        'class' => ImportCompleted::class,
        'permission' => 'settings.data_transfer.imports',
        'events' => ['data_transfer.imports.completed'],
    ],
];
