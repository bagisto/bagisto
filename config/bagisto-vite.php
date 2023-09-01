<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bagisto Vite Configuration
    |--------------------------------------------------------------------------
    |
    | Please add your Vite registry here to seamlessly support the `bagisto_assets` function.
    |
    */

    'viters' => [
        'admin' => [
            'hot_file'        => 'admin-default-vite.hot',
            'build_directory' => 'themes/admin/default/build',
        ],

        'shop' => [
            'hot_file'        => 'shop-default-vite.hot',
            'build_directory' => 'themes/shop/default/build',
        ],
    ],
];
