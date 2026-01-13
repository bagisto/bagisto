<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Interfaces\ImageInterface;

class Medium
{
    /**
     * Apply filter.
     *
     * @param  ImageInterface|\Intervention\Image\CachedImage  $image
     * @return ImageInterface|\Intervention\Image\CachedImage
     */
    public function applyFilter($image)
    {
        /**
         * If the current url is product image
         */
        if (Str::contains(url()->current(), '/product')) {
            $width = core()->getConfigData('catalog.products.cache_medium_image.width') != ''
                ? core()->getConfigData('catalog.products.cache_medium_image.width')
                : 350;

            $height = core()->getConfigData('catalog.products.cache_medium_image.height') != ''
                ? core()->getConfigData('catalog.products.cache_medium_image.height')
                : 360;

            return $image->cover((int) $width, (int) $height);
        } elseif (Str::contains(url()->current(), '/category')) {
            return $image->cover(110, 110);
        } elseif (Str::contains(url()->current(), '/attribute_option')) {
            return $image->cover(210, 210);
        }

        /**
         * Slider image dimensions
         */
        return $image->cover(1024, 372);
    }
}
