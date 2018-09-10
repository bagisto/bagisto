<?php

use Webkul\Theme\Themes;

if (! function_exists('themes')) {
    function themes()
    {
        return new Themes;
    }
}

if (!function_exists('bagisto_asset')) {
    function bagisto_asset($path, $secure = null)
    {
        return app()->make('themes')->url($path, $secure);
    }
}