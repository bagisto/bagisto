<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Error;
use Illuminate\Image\Image;

class FailingFilter
{
    /**
     * Fail the way a theme's own filter might, before the image is processed.
     */
    public function applyFilter(Image $image): Image
    {
        throw new Error('The filter could not run.');
    }
}
