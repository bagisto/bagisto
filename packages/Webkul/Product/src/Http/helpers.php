<?php

use Webkul\Product\Facades\ProductImage;
use Webkul\Product\Facades\ProductVideo;
use Webkul\Product\Helpers\Toolbar;

if (! function_exists('product_image')) {
    /**
     * Product image helper.
     *
     * @return \Webkul\Product\ProductImage
     */
    function product_image()
    {
        return ProductImage::getFacadeRoot();
    }
}

if (! function_exists('product_video')) {
    /**
     * Product video helper.
     *
     * @return \Webkul\Product\ProductVideo
     */
    function product_video()
    {
        return ProductVideo::getFacadeRoot();
    }
}

if (! function_exists('product_toolbar')) {
    /**
     * Product tolbar helper.
     *
     * @return \Webkul\Product\Helpers\Toolbar
     */
    function product_toolbar()
    {
        return app()->make(Toolbar::class);
    }
}
