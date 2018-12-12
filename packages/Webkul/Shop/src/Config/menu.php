<?php

return [
    [
        'key' => 'account',
        'name' => 'My Account',
        'route' =>'customer.profile.index',
        'sort' => 1
    ], [
        'key' => 'account.profile',
        'name' => 'Profile',
        'route' =>'customer.profile.index',
        'sort' => 1
    ], [
        'key' => 'account.address',
        'name' => 'Address',
        'route' =>'customer.address.index',
        'sort' => 2
    ], [
        'key' => 'account.reviews',
        'name' => 'Reviews',
        'route' =>'customer.reviews.index',
        'sort' => 3
    ], [
        'key' => 'account.wishlist',
        'name' => 'Wishlist',
        'route' =>'customer.wishlist.index',
        'sort' => 4
    ]
];

?>