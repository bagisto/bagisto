<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Support\Str;
use Intervention\Image\Interfaces\ImageInterface;

class Large
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
            $width = core()->getConfigData('catalog.products.cache_large_image.width') != ''
                ? core()->getConfigData('catalog.products.cache_large_image.width')
                : 560;

            $height = core()->getConfigData('catalog.products.cache_large_image.height') != ''
                ? core()->getConfigData('catalog.products.cache_large_image.height')
                : 610;

            return $image->cover((int) $width, (int) $height);
        } elseif (Str::contains(url()->current(), '/category')) {
            return $image->cover(165, 165);
        } elseif (Str::contains(url()->current(), '/attribute_option')) {
            return $image->cover(330, 330);
        }

        /**
         * Slider image dimensions
         */
        return $image->cover(1280, 467);
    }
}
