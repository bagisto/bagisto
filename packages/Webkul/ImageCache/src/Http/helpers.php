<?php

use Intervention\Image\ImageManager;
use Webkul\ImageCache\ImageUrlBuilder;

if (! function_exists('image_manager')) {
    /**
     * Get the image manager instance.
     */
    function image_manager(): ImageManager
    {
        return app('image_manager');
    }
}

if (! function_exists('image_urls')) {
    /**
     * Get a stored image's url through the core sizes, the templates the current theme lists under the
     * given key of `customize.image_cache`, and the original.
     */
    function image_urls(string $path, ?string $key = null): array
    {
        return app(ImageUrlBuilder::class)->urls($path, $key);
    }
}
