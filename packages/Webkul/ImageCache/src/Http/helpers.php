<?php

use Intervention\Image\ImageManager;

if (! function_exists('image_manager')) {
    /**
     * Get the image manager instance.
     */
    function image_manager(): ImageManager
    {
        return app('image_manager');
    }
}
