<?php

namespace Webkul\Product\CacheFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Large implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $width = empty(core()->getConfigData('catalog.products.cache-large-image.width')) === false ? core()->getConfigData('catalog.products.cache-large-image.width') : 480;

        $height = empty(core()->getConfigData('catalog.products.cache-large-image.height')) === false ? core()->getConfigData('catalog.products.cache-large-image.height') : 480;

        return $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
