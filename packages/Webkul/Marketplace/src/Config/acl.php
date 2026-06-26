<?php

return [
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.acl.marketplace',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 7,
    ],
    [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.acl.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
    ],
    [
        'key'   => 'marketplace.sellers.view',
        'name'  => 'marketplace::app.admin.acl.view',
        'route' => 'admin.marketplace.sellers.view',
        'sort'  => 1,
    ],
    [
        'key'   => 'marketplace.sellers.approve',
        'name'  => 'marketplace::app.admin.acl.approve',
        'route' => 'admin.marketplace.sellers.approve',
        'sort'  => 2,
    ],
    [
        'key'   => 'marketplace.commissions',
        'name'  => 'marketplace::app.admin.acl.commissions',
        'route' => 'admin.marketplace.commissions.index',
        'sort'  => 2,
    ],
    [
        'key'   => 'marketplace.payouts',
        'name'  => 'marketplace::app.admin.acl.payouts',
        'route' => 'admin.marketplace.payouts.index',
        'sort'  => 3,
    ],
];
