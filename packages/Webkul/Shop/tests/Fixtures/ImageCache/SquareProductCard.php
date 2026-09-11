<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Intervention\Image\Interfaces\ImageInterface;

class SquareProductCard
{
    /**
     * Crop the image to a square product card.
     */
    public function applyFilter(ImageInterface $image): ImageInterface
    {
        return $image->cover(200, 200);
    }
}
