<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class CategoryCard
{
    /**
     * Crop the image to a square category card.
     */
    public function applyFilter(Image $image): Image
    {
        return $image->cover(180, 180);
    }
}
