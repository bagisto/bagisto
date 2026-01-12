<?php

namespace Webkul\ImageCache\Templates;

use Intervention\Image\Interfaces\ImageInterface;

/**
 * Large image template filter.
 *
 * Creates images at 600x600 pixels.
 */
class Large
{
    /**
     * The width for large images.
     */
    protected int $width = 600;

    /**
     * The height for large images.
     */
    protected int $height = 600;

    /**
     * Apply the filter to the image.
     */
    public function applyFilter(ImageInterface $image): ImageInterface
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
