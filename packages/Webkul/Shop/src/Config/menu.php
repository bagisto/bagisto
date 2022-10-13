<?php

return [
    [
        'key'   => 'account',
        'name'  => 'shop::app.layouts.my-account',
        'route' =>'shop.customer.profile.index',
        'sort'  => 1,
    ], [
        'key'   => 'account.profile',
        'name'  => 'shop::app.layouts.profile',
        'route' =>'shop.customer.profile.index',
        'sort'  => 1,
    ], [
        'key'   => 'account.address',
        'name'  => 'shop::app.layouts.address',
        'route' =>'shop.customer.addresses.index',
        'sort'  => 2,
    ], [
        'key'   => 'account.reviews',
        'name'  => 'shop::app.layouts.reviews',
        'route' =>'shop.customer.reviews.index',
        'sort'  => 3,
    ], [
        'key'   => 'account.wishlist',
        'name'  => 'shop::app.layouts.wishlist',
        'route' =>'shop.customer.wishlist.index',
        'sort'  => 4,
    ], [
        'key'   => 'account.compare',
        'name'  => 'shop::app.customer.compare.text',
        'route' =>'velocity.customer.product.compare',
        'sort'  => 5,
    ], [
        'key'   => 'account.orders',
        'name'  => 'shop::app.layouts.orders',
        'route' =>'shop.customer.orders.index',
        'sort'  => 6,
    ], [
        'key'   => 'account.downloadables',
        'name'  => 'shop::app.layouts.downloadable-products',
        'route' =>'shop.customer.downloadable_products.index',
        'sort'  => 7,
    ]
];

?>