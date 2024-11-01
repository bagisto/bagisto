<?php

namespace Webkul\Shop\CacheFilters;

use Intervention\Image\Image;

class Large implements CacheFilterInterface
{
    public function handle(Image $image, ?string $preset = null): Image
    {
        [$width, $height] = match ($preset) {
            'product' => [
                core()->getConfigData('catalog.products.cache_large_image.width') ?: 560,
                core()->getConfigData('catalog.products.cache_large_image.height') ?: 610,
            ],
            'category'                                           => [165, 165],
            'attribute_option'                                   => [330, 330],
            default                                              => [1280, 467],
        };

        /**
         * Slider image dimensions
         */
        return $image->resize($width, $height);
    }
}
