<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Laravel processes images through either PHP's "GD Library" or "Imagick".
    | Pick the one your PHP build provides; GD is the more commonly compiled
    | of the two and is used by default.
    |
    | Supported: "gd", "imagick"
    |
    */

    'default' => env('IMAGE_DRIVER', 'gd'),

];
