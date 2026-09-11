<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Intervention\Image\Interfaces\ImageInterface;

class HiddenFilter
{
    /**
     * Resize the image, out of the image cache's reach.
     */
    protected function applyFilter(ImageInterface $image): ImageInterface
    {
        return $image->cover(10, 10);
    }
}
