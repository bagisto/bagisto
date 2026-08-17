<?php

namespace Webkul\ImageCache\Templates;

use Illuminate\Image\Image;

/**
 * Medium image template filter.
 *
 * Creates images at 300x300 pixels.
 */
class Medium
{
    /**
     * The width for medium images.
     */
    protected int $width = 300;

    /**
     * The height for medium images.
     */
    protected int $height = 300;

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
