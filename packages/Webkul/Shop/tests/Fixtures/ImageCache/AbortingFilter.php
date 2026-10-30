<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class AbortingFilter
{
    /**
     * Abort with a status the way a theme's own filter might, before the image is processed.
     */
    public function applyFilter(Image $image): Image
    {
        abort(403);
    }
}
