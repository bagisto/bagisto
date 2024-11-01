<?php

namespace Webkul\Shop\CacheFilters;

use Intervention\Image\Image;

class Medium implements CacheFilterInterface
{
    public function handle(Image $image, ?string $preset = null): Image
    {
        [$width, $height] = match ($preset) {
            'product' => [
                core()->getConfigData('catalog.products.cache_medium_image.width') ?: 350,
                core()->getConfigData('catalog.products.cache_large_image.height') ?: 360,
            ],
            'category'         => [110, 110],
            'attribute_option' => [210, 210],
            default            => [1024, 372],
        };

        return $image->resize($width, $height);
    }
}
