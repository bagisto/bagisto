<?php

use Webkul\Product\ProductImage;
use Webkul\Product\ProductVideo;

if (! function_exists('productimage')) {
    function productimage() {
        return app()->make(ProductImage::class);
    }
}

if (! function_exists('productvideo')) {
    function productvideo() {
        return app()->make(ProductVideo::class);
    }
}
?>