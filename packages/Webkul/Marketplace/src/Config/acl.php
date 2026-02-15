<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marketplace
    |--------------------------------------------------------------------------
    */
    [
        'key'   => 'marketplace',
        'name'  => 'marketplace::app.admin.acl.marketplace',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 10,
    ], [
        'key'   => 'marketplace.sellers',
        'name'  => 'marketplace::app.admin.acl.sellers',
        'route' => 'admin.marketplace.sellers.index',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.sellers.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketplace.sellers.edit',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.sellers.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketplace.sellers.delete',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.products',
        'name'  => 'marketplace::app.admin.acl.seller-products',
        'route' => 'admin.marketplace.seller-products.index',
        'sort'  => 2,
    ], [
        'key'   => 'marketplace.orders',
        'name'  => 'marketplace::app.admin.acl.seller-orders',
        'route' => 'admin.marketplace.seller-orders.index',
        'sort'  => 3,
    ], [
        'key'   => 'marketplace.transactions',
        'name'  => 'marketplace::app.admin.acl.transactions',
        'route' => 'admin.marketplace.transactions.index',
        'sort'  => 4,
    ], [
        'key'   => 'marketplace.transactions.create',
        'name'  => 'admin::app.acl.create',
        'route' => 'admin.marketplace.transactions.store',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.reviews',
        'name'  => 'marketplace::app.admin.acl.seller-reviews',
        'route' => 'admin.marketplace.reviews.index',
        'sort'  => 5,
    ], [
        'key'   => 'marketplace.reviews.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => 'admin.marketplace.reviews.update',
        'sort'  => 1,
    ], [
        'key'   => 'marketplace.reviews.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => 'admin.marketplace.reviews.delete',
        'sort'  => 2,
    ],
];
