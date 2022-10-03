<?php

use Webkul\Product\ProductImage;
use Webkul\Product\ProductVideo;

if (! function_exists('product_image')) {
    function product_image() {
        return app()->make(ProductImage::class);
    }
}

if (! function_exists('product_video')) {
    function product_video() {
        return app()->make(ProductVideo::class);
    }
}
?>