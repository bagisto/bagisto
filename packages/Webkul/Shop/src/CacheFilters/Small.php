<?php

namespace Webkul\Shop\CacheFilters;

use Illuminate\Image\Image;
use Illuminate\Support\Str;

class Small
{
    /**
     * Apply the filter to the image, at the size the subject it is served for is shown at.
     */
    public function applyFilter(Image $image): Image
    {
        $url = url()->current();

        return match (true) {
            Str::contains($url, '/products') => $image->cover(100, 100),
            Str::contains($url, '/categories') => $image->cover(80, 80),
            Str::contains($url, '/attribute-options') => $image->cover(60, 60),
            default => $image->cover(768, 280),
        };
    }
}
