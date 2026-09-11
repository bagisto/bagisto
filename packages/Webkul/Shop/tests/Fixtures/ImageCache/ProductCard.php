<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Intervention\Image\Interfaces\ImageInterface;

class ProductCard
{
    /**
     * Crop the image to a portrait product card.
     */
    public function applyFilter(ImageInterface $image): ImageInterface
    {
        return $image->cover(240, 320);
    }
}
