<?php

namespace Webkul\Shop\CacheFilters;

use Intervention\Image\Image;

class Max implements CacheFilterInterface
{
    public function handle(Image $image, ?string $preset = null): Image
    {
        [$width, $height] = match ($preset) {
            'product'          => [2048, 2048],
            'category'         => [270, 270],
            'attribute_option' => [500, 500],
            default            => [3840, 2160],
        };

        /**
         * Scale down the image to a given max width and height.
         */
        return $image->scaleDown($width, $height);
    }
}
