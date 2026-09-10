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
                | How this theme resizes the images served at "cache/{template}/{path}"
                | for the channels running it, on top of the core image cache in
                | config/imagecache.php. Admin pages always use the core templates.
                |
                */

                'image_cache' => [

                    /*
                    |--------------------------------------------------------------------------
                    | Templates
                    |--------------------------------------------------------------------------
                    |
                    | The image cache templates this theme registers, keyed by the name used
                    | in the url, merged over the core templates. Register a core name
                    | (small, medium, large) to override it, or a new name to add a template.
                    | A name uses letters, digits, dashes and underscores, and must not be
                    | original, download or logo. Each template is a class with a public
                    | applyFilter() method.
                    |
                    | Every name left out falls back to the core template of that name.
                    |
                    | e.g. 'small' => \Webkul\Fashion\ImageTemplates\Small::class,
                    |      'product_card' => \Webkul\Fashion\ImageTemplates\ProductCard::class,
                    |
                    */

                    'templates' => [],

                    /*
                    |--------------------------------------------------------------------------
                    | Product Images
                    |--------------------------------------------------------------------------
                    |
                    | The template names product image urls carry besides the core small,
                    | medium, large and original, as "{name}_image_url" in the product image
                    | helper and the storefront product APIs. List only templates meant for
                    | product images; a name must be registered under "templates" or in
                    | config/imagecache.php.
                    |
                    | e.g. 'product_card', which adds "product_card_image_url" to every
                    |      product image, served at "cache/product_card/{path}".
                    |
                    */

                    'product_images' => [],

                    /*
                    |--------------------------------------------------------------------------
                    | Category Images
                    |--------------------------------------------------------------------------
                    |
                    | The template names category logo and banner urls carry besides the core
                    | small, medium, large and original, as "{name}_image_url" in the
                    | storefront category API. List only templates meant for category images;
                    | a name must be registered under "templates" or in config/imagecache.php.
                    |
                    | e.g. 'category_card', which adds "category_card_image_url" to every
                    |      category logo and banner, served at "cache/category_card/{path}".
                    |
                    */

                    'category_images' => [],

                    /*
                    |--------------------------------------------------------------------------
                    | Swatch Images
                    |--------------------------------------------------------------------------
                    |
                    | The template names image swatch urls carry besides the core small,
                    | medium, large and original, as "{name}_image_url" in the "swatch_image"
                    | of every configurable product option. List only templates meant for
                    | swatches; a name must be registered under "templates" or in
                    | config/imagecache.php.
                    |
                    | e.g. 'swatch_card', which adds "swatch_card_image_url" to every image
                    |      swatch, served at "cache/swatch_card/{path}".
                    |
                    */

                    'swatch_images' => [],
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
