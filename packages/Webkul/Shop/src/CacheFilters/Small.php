<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Small implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @param  \Intervention\Image\Image  $image
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
        }

        /**
         * Slider image dimensions
         */
        return $image->fit(525, 191);
    }
}
