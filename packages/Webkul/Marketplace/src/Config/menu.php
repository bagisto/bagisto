<?php

return [
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.layouts.sidebar.marketplace',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 7,
        'icon'  => 'icon-store',
    ],
    [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.layouts.sidebar.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
        'icon'  => '',
    ],
    [
        'key'   => 'marketplace.commissions',
        'name'  => 'marketplace::app.admin.layouts.sidebar.commissions',
        'route' => 'admin.marketplace.commissions.index',
        'sort'  => 2,
        'icon'  => '',
    ],
    [
        'key'   => 'marketplace.subscriptions',
        'name'  => 'marketplace::app.admin.layouts.sidebar.subscriptions',
        'route' => 'admin.marketplace.subscriptions.index',
        'sort'  => 3,
        'icon'  => '',
    ],
    [
        'key'   => 'marketplace.payouts',
        'name'  => 'marketplace::app.admin.layouts.sidebar.payouts',
        'route' => 'admin.marketplace.payouts.index',
        'sort'  => 4,
        'icon'  => '',
    ],
];
