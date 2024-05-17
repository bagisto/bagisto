<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Small implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
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

            return $image->fit($width, $height);
        } elseif (Str::contains(url()->current(), '/category')) {
            return $image->fit(80, 80);
        } elseif (Str::contains(url()->current(), '/attribute_option')) {
            return $image->fit(60, 60);
        }

        /**
         * Slider image dimensions
         */
        return $image->fit(525, 191);
    }
}
