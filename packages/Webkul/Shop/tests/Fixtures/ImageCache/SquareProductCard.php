<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class SquareProductCard
{
    /**
     * Crop the image to a square product card.
     */
    public function applyFilter(Image $image): Image
    {
        return $image->cover(200, 200);
    }
}
