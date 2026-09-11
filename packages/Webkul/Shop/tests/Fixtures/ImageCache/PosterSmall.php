<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Webkul\ImageCache\Templates\Small;

class PosterSmall extends Small
{
    /**
     * The width for small images.
     */
    protected int $width = 300;

    /**
     * The height for small images.
     */
    protected int $height = 200;
}
