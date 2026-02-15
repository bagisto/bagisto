<?php

return [
    /**
     * Marketplace.
     */
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.layouts.marketplace',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 7,
        'icon'  => 'icon-customer-2',
    ], [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.layouts.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
        'icon'  => '',
    ], [
        'key'   => 'marketplace.products',
        'name'  => 'marketplace::app.admin.layouts.seller-products',
        'route' => 'admin.marketplace.seller-products.index',
        'sort'  => 2,
        'icon'  => '',
    ], [
        'key'   => 'marketplace.orders',
        'name'  => 'marketplace::app.admin.layouts.seller-orders',
        'route' => 'admin.marketplace.seller-orders.index',
        'sort'  => 3,
        'icon'  => '',
    ], [
        'key'   => 'marketplace.transactions',
        'name'  => 'marketplace::app.admin.layouts.transactions',
        'route' => 'admin.marketplace.transactions.index',
        'sort'  => 4,
        'icon'  => '',
    ], [
        'key'   => 'marketplace.reviews',
        'name'  => 'marketplace::app.admin.layouts.seller-reviews',
        'route' => 'admin.marketplace.reviews.index',
        'sort'  => 5,
        'icon'  => '',
    ],
];
