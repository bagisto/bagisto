<?php

namespace Webkul\Shop\CacheFilters;

use Intervention\Image\Image;

interface CacheFilterInterface
{
    public function handle(Image $image, ?string $preset = null): Image;
}
