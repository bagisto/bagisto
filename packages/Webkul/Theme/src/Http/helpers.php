<?php

if (! function_exists('themes')) {
    function themes()
    {
        return app()->make('themes');
    }
}

if (!function_exists('bagisto_asset')) {
    function bagisto_asset($path, $secure = null)
    {
        return app()->make('themes')->url($path, $secure);
    }
}