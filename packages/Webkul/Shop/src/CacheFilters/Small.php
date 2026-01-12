<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Interfaces\ImageInterface;

class Small
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
            $width = core()->getConfigData('catalog.products.cache_small_image.width')
                ? core()->getConfigData('catalog.products.cache_small_image.width')
                : 100;

            $height = core()->getConfigData('catalog.products.cache_small_image.height')
                ? core()->getConfigData('catalog.products.cache_small_image.height')
                : 100;

            return $image->cover((int) $width, (int) $height);
        } elseif (Str::contains(url()->current(), '/category')) {
            return $image->cover(80, 80);
        } elseif (Str::contains(url()->current(), '/attribute_option')) {
            return $image->cover(60, 60);
        }

        /**
         * Slider image dimensions
         */
        return $image->cover(525, 191);
    }
}
