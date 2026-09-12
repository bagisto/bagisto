<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class HiddenFilter
{
    /**
     * Resize the image, out of the image cache's reach.
     */
    protected function applyFilter(Image $image): Image
    {
        return $image->cover(10, 10);
    }
}
