<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Groups
    |--------------------------------------------------------------------------
    |
    | The groups the admin panel lists its notification types under. The keys
    | each entry accepts are documented in the Notification package's
    | `Config/notification-groups.php`.
    |
    */

    'orders' => [
        'name' => 'admin::app.notifications.groups.orders',
        'icon' => 'icon-sales',
        'sort' => 1,
    ],

    'customers' => [
        'name' => 'admin::app.notifications.groups.customers',
        'icon' => 'icon-customer',
        'sort' => 2,
    ],

    'reviews' => [
        'name' => 'admin::app.notifications.groups.reviews',
        'icon' => 'icon-star',
        'sort' => 3,
    ],

    'returns' => [
        'name' => 'admin::app.notifications.groups.returns',
        'icon' => 'icon-repeat',
        'sort' => 4,
    ],

    'gdpr' => [
        'name' => 'admin::app.notifications.groups.gdpr',
        'icon' => 'icon-information',
        'sort' => 5,
    ],

    'imports' => [
        'name' => 'admin::app.notifications.groups.imports',
        'icon' => 'icon-folder',
        'sort' => 6,
    ],
];
