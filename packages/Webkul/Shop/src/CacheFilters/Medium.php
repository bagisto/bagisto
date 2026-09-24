<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Image\Image;
use Illuminate\Support\Str;

class Medium
{
    /**
     * Apply the filter to the image, at the size the subject it is served for is shown at.
     */
    public function applyFilter(Image $image): Image
    {
        $url = url()->current();

        return match (true) {
            Str::contains($url, '/products') => $image->cover(350, 360),
            Str::contains($url, '/categories') => $image->cover(110, 110),
            Str::contains($url, '/attribute-options') => $image->cover(210, 210),
            default => $image->cover(1024, 372),
        };
    }
}
