<?php

return [
    [
        'key'   => 'account',
        'name'  => 'shop::app.layouts.my-account',
        'route' => 'shop.customer.profile.index',
        'icon'  => '',
        'sort'  => 1,
    ], [
        'key'   => 'account.profile',
        'name'  => 'shop::app.layouts.profile',
        'route' => 'shop.customer.profile.index',
        'icon'  => '',
        'sort'  => 1,
    ], [
        'key'   => 'account.address',
        'name'  => 'shop::app.layouts.address',
        'route' => 'shop.customer.addresses.index',
        'icon'  => '',
        'sort'  => 2,
    ], [
        'key'   => 'account.orders',
        'name'  => 'shop::app.layouts.orders',
        'route' => 'shop.customers.account.orders.index',
        'icon'  => '',
        'sort'  => 3,
    ], [
        'key'   => 'account.downloadables',
        'name'  => 'shop::app.layouts.downloadable-products',
        'route' => 'shop.customer.downloadable_products.index',
        'icon'  => '',
        'sort'  => 4,
    ], [
        'key'   => 'account.reviews',
        'name'  => 'shop::app.layouts.reviews',
        'route' => 'shop.customer.reviews.index',
        'icon'  => '',
        'sort'  => 5,
    ], [
        'key'   => 'account.wishlist',
        'name'  => 'shop::app.layouts.wishlist',
        'route' => 'shop.customer.wishlist.index',
        'icon'  => '',
        'sort'  => 6,
    ], [
        'key'   => 'account.compare',
        'name'  => 'shop::app.customer.compare.text',
        'route' => 'shop.customers.account.orders.index',
        'icon'  => '',
        'sort'  => 7,
    ],
];
