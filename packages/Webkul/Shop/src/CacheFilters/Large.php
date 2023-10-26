<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class Large implements FilterInterface
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
            $width = core()->getConfigData('catalog.products.cache_large_image.width') != ''
                ? core()->getConfigData('catalog.products.cache_large_image.width')
                : 560;

            $height = core()->getConfigData('catalog.products.cache_large_image.height') != ''
                ? core()->getConfigData('catalog.products.cache_large_image.height')
                : 610;

            return $image->fit($width, $height);
        }

        /**
         * Slider image dimensions
         */
        return $image->fit(1280, 467);
    }
}
