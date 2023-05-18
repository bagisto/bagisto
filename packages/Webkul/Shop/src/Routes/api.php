<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\ProductController;

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {

    /**
     * Categories and products.
     */
    Route::get('product-details/{slug}', [ProductController::class, 'fetchProductDetails'])
        ->name('shop.products');

});

?>