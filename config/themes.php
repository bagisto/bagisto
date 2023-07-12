<?php

return [
    'default' => 'default',

    'themes' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path'  => 'resources/themes/default/views',

            'vite'        => [
                'hot_file'        => 'shop-default-vite.hot',
                'build_directory' => 'themes/shop/default/build',
            ],
        ],
    ],

    'admin-default' => 'default',

    'admin-themes' => [
        'default' => [
            'name'        => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path'  => 'resources/admin-themes/default/views',

            'vite'        => [
                'hot_file'        => 'admin-default-vite.hot',
                'build_directory' => 'themes/admin/default/build',
            ],
        ],
    ],
];
