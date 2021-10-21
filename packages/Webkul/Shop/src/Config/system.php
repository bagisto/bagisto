<?php

return [
    [
        'key'    => 'general.content.shop',
        'name'   => 'shop::app.products.settings',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'compare_option',
                'title'         => 'shop::app.products.compare_options',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'wishlist_option',
                'title'         => 'shop::app.products.wishlist-options',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ], [
                'name'          => 'image_search',
                'title'         => 'shop::app.search.image-search-option',
                'type'          => 'boolean',
                'locale_based'  => true,
                'channel_based' => true,
            ],
        ],
    ]
];

?>