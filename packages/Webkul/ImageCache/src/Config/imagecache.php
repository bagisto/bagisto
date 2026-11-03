<?php

use Webkul\ImageCache\Templates\Large;
use Webkul\ImageCache\Templates\Medium;
use Webkul\ImageCache\Templates\Small;

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
    | Set it to null or leave it empty to turn the route off: every image url
    | then links the stored file itself, and the admin panel leaves out the
    | Bagisto logo.
    |
    */

    'route' => 'cache',

    /*
    |--------------------------------------------------------------------------
    | Storage Paths
    |--------------------------------------------------------------------------
    |
    | The configured filesystem disk is read first, so a store on object storage
    | is served the same way a local one is. These paths are searched after it,
    | which covers a file published beside the application.
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
    | applied, by its fully qualified name. An entry the image cache cannot
    | apply is reported, and small, medium and large then fall back to the
    | templates in Webkul\ImageCache\Templates.
    |
    */

    'templates' => [
        'small' => Small::class,
        'medium' => Medium::class,
        'large' => Large::class,
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
];
