<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Illuminate\Image\Image;

class InjectedFilter
{
    /**
     * Create the filter with the template the container injects for it to crop through.
     */
    public function __construct(protected SquareProductCard $card) {}

    /**
     * Crop the image through the injected template.
     */
    public function applyFilter(Image $image): Image
    {
        return $this->card->applyFilter($image);
    }
}
