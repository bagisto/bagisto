<?php

return [
    [
        'key'    => 'general.content.shop',
        'name'   => 'shop::app.configurations.settings-title',
        'info'   => 'shop::app.configurations.settings-title-info',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'compare_option',
                'title'         => 'shop::app.products.configurations.compare-options',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'wishlist_option',
                'title'         => 'shop::app.products.configurations.wishlist-options',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'image_search',
                'title'         => 'shop::app.search.configurations.image-search-option',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ],
];
