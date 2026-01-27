<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Name of Route
    |--------------------------------------------------------------------------
    |
    | Enter the route name to enable dynamic image cache manipulation.
    |
    | This handle will define the first part of the URI:
    |
    | {route}/{template}/{filename}
    |
    | Examples: "images", "img/cache"
    |
    */

    'route' => 'cache',

    /*
    |--------------------------------------------------------------------------
    | Storage Paths
    |--------------------------------------------------------------------------
    |
    | The following paths will be searched for the image filename submitted
    | by the URI.
    |
    | Define as many directories as you like.
    |
    */

    'paths' => [
        storage_path('app/public'),
        public_path('storage'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Manipulation Templates
    |--------------------------------------------------------------------------
    |
    | Here you may specify your own manipulation filter templates.
    | The keys of this array will define which templates are available
    | in the URI:
    |
    | {route}/{template}/{filename}
    |
    | The values of this array will define which filter class will be
    | applied, by its fully qualified name.
    |
    */

    'templates' => [
        'small' => Webkul\ImageCache\Templates\Small::class,
        'medium' => Webkul\ImageCache\Templates\Medium::class,
        'large' => Webkul\ImageCache\Templates\Large::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Cache Lifetime
    |--------------------------------------------------------------------------
    |
    | Lifetime in minutes of the images handled by the image cache route.
    |
    */

    'lifetime' => 43200,

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | Optionally specify a custom cache driver to use for image caching.
    | Set to null to use the default Laravel cache driver.
    |
    */

    'cache_driver' => null,
];
