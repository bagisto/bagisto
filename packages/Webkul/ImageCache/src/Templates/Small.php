<?php

namespace Webkul\ImageCache\Templates;

use Illuminate\Image\Image;

/**
 * Small image template filter.
 *
 * Creates thumbnails at 100x100 pixels.
 */
class Small
{
    /**
     * The width for small images.
     */
    protected int $width = 100;

    /**
     * The height for small images.
     */
    protected int $height = 100;

    /**
     * Apply the filter to the image.
     */
    public function applyFilter(Image $image): Image
    {
        return $image->cover($this->width, $this->height);
    }

    /**
     * Get the configured width.
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Get the configured height.
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}
