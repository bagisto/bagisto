<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class SizedFilter
{
    /**
     * Create the filter with the size it crops to, which the container has no way to supply.
     */
    public function __construct(protected int $size) {}

    /**
     * Crop the image to a square of the size the filter was created with.
     */
    public function applyFilter(Image $image): Image
    {
        return $image->cover($this->size, $this->size);
    }
}
