<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Medium implements FilterInterface
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
            $width = core()->getConfigData('catalog.products.cache_medium_image.width') != ''
                ? core()->getConfigData('catalog.products.cache_medium_image.width')
                : 350;

            $height = core()->getConfigData('catalog.products.cache_medium_image.height') != ''
                ? core()->getConfigData('catalog.products.cache_medium_image.height')
                : 360;

            return $image->fit($width, $height);
        }

        /**
         * Slider image dimensions
         */
        return $image->fit(1024, 372);
    }
}
