<?php

use Webkul\Product\Helpers\Toolbar;
use Webkul\Product\ProductImage;
use Webkul\Product\ProductVideo;

if (! function_exists('product_image')) {
    /**
     * Product image helper.
     */
    function product_image(): ProductImage
    {
        return app()->make(ProductImage::class);
    }
}

if (! function_exists('product_video')) {
    /**
     * Product video helper.
     */
    function product_video(): ProductVideo
    {
        return app()->make(ProductVideo::class);
    }
}

if (! function_exists('product_toolbar')) {
    /**
     * Product tolbar helper.
     */
    function product_toolbar(): Toolbar
    {
        return app()->make(Toolbar::class);
    }
}
