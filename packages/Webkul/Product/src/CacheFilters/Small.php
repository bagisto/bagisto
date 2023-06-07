<?php

namespace Webkul\Product\CacheFilters;

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
        $width = core()->getConfigData('catalog.products.cache-small-image.width')
            ? core()->getConfigData('catalog.products.cache-small-image.width')
            : 100;

        $height = core()->getConfigData('catalog.products.cache-small-image.height')
            ? core()->getConfigData('catalog.products.cache-small-image.height')
            : 100;

        return $image->fit($width, $height);
    }
}
