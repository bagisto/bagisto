<?php

namespace Webkul\Shop\CacheFilters;

use Intervention\Image\Image;

class Small implements CacheFilterInterface
{
    public function handle(Image $image, ?string $preset = null): Image
    {
        [$width, $height] = match ($preset) {
            'product' => [
                core()->getConfigData('catalog.products.cache_small_image.width') ?: 100,
                core()->getConfigData('catalog.products.cache_small_image.height') ?: 100,
            ],
            'category'         => [80, 80],
            'attribute_option' => [60, 60],
            default            => [525, 191],
        };

        return $image->resize($width, $height);
    }
}
