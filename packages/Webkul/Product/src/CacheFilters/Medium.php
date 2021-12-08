<?php

namespace Webkul\Product\CacheFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Medium implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $width = empty(core()->getConfigData('catalog.products.cache-medium-image.width')) === false ? core()->getConfigData('catalog.products.cache-medium-image.width') : 280;

        $height = empty(core()->getConfigData('catalog.products.cache-medium-image.height')) === false ? core()->getConfigData('catalog.products.cache-medium-image.height') : 280;

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $image->resizeCanvas($width, $height, 'center', false, '#fff');
    }
}
