<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class RespondingFilter
{
    /**
     * Abort with a response of its own the way a theme's own filter might, before the image is processed.
     */
    public function applyFilter(Image $image): Image
    {
        abort(response()->noContent());
    }
}
