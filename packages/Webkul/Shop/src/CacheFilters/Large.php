<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Image\Image;
use Illuminate\Support\Str;

class Large
{
    /**
     * Apply the filter to the image, at the size the subject it is served for is shown at.
     */
    public function applyFilter(Image $image): Image
    {
        $url = url()->current();

        return match (true) {
            Str::contains($url, '/products') => $image->cover(560, 610),
            Str::contains($url, '/categories') => $image->cover(165, 165),
            Str::contains($url, '/attribute-options') => $image->cover(330, 330),
            default => $image->cover(1280, 467),
        };
    }
}
