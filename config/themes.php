<?php

use Webkul\Theme\Enums\SectionTypeEnum;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Shop Theme
    |--------------------------------------------------------------------------
    |
    | The code of the storefront theme a channel falls back to when the theme
    | it is set to is not registered below.
    |
    */

    'shop-default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Shop Themes
    |--------------------------------------------------------------------------
    |
    | Every storefront theme installed on this store, keyed by its theme code.
    | A theme is registered with its name, the paths of its assets and views,
    | its Vite build, and — optionally — what it customizes in the storefront.
    |
    */

    'shop' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/shop/default',
            'views_path' => 'resources/themes/default/views',

            'vite' => [
                'hot_file' => 'shop-default-vite.hot',
                'build_directory' => 'themes/shop/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],

            /*
            |--------------------------------------------------------------------------
            | Theme Customization
            |--------------------------------------------------------------------------
            |
            | Everything this theme customizes in the storefront is registered here,
            | in one place, so nothing has to be registered anywhere else. Every key
            | is optional: whatever a theme leaves out falls back to the core.
            |
            */

            'customize' => [
                /*
                |--------------------------------------------------------------------------
                | Sections
                |--------------------------------------------------------------------------
                |
                | The section types the Appearance editor offers for this theme, in the
                | order its Add Section tiles show them. List a core type as its
                | SectionTypeEnum case, and a theme's own type as its class, which must
                | extend Webkul\Theme\Sections\SectionType.
                |
                | Leave this key out to offer every core section type in enum order.
                |
                */

                'sections' => [
                    SectionTypeEnum::IMAGE_CAROUSEL,
                    SectionTypeEnum::PRODUCT_CAROUSEL,
                    SectionTypeEnum::CATEGORY_CAROUSEL,
                    SectionTypeEnum::FOOTER_LINKS,
                    SectionTypeEnum::STATIC_CONTENT,
                    SectionTypeEnum::SERVICES_CONTENT,
                ],

                /*
                |--------------------------------------------------------------------------
                | Image Cache
                |--------------------------------------------------------------------------
                |
                | The image cache templates served at "cache/{template}/{path}" for the
                | channels running this theme, merged over the core templates in
                | config/imagecache.php. Register a core name (small, medium, large) to
                | override it, or a new name to add a template. Each template is a class
                | with a public applyFilter() method.
                |
                | Every name left out falls back to the core template of that name.
                |
                */

                'image_cache' => [
                    'templates' => [],
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Admin Theme
    |--------------------------------------------------------------------------
    |
    | The code of the theme the admin panel is rendered with.
    |
    */

    'admin-default' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Admin Themes
    |--------------------------------------------------------------------------
    |
    | Every admin panel theme installed on this store, keyed by its theme code,
    | with its name, the paths of its assets and views, and its Vite build.
    |
    */

    'admin' => [
        'default' => [
            'name' => 'Default',
            'assets_path' => 'public/themes/admin/default',
            'views_path' => 'resources/admin-themes/default/views',

            'vite' => [
                'hot_file' => 'admin-default-vite.hot',
                'build_directory' => 'themes/admin/default/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],
        ],
    ],
];
