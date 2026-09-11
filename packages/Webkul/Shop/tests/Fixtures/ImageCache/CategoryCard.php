<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Intervention\Image\Interfaces\ImageInterface;

class CategoryCard
{
    /**
     * Crop the image to a square category card.
     */
    public function applyFilter(ImageInterface $image): ImageInterface
    {
        return $image->cover(180, 180);
    }
}
