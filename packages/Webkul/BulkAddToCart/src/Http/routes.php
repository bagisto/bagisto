<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {

    Route::prefix('customer')->group(function () {

        Route::group(['middleware' => ['customer']], function () {

            Route::get('bulk-add-to-cart', 'Webkul\BulkAddToCart\Http\Controllers\BulkAddToCartController@create')->defaults('_config', [
                'view' => 'bulkaddtocart::products.bulk-add-to-cart'
            ])->name('cart.bulk-add-to-cart.create');

            Route::post('bulk-add-to-cart', 'Webkul\BulkAddToCart\Http\Controllers\BulkAddToCartController@store')->defaults('_config', [
                'redirect' => 'shop.checkout.cart.index'
            ])->name('cart.bulk-add-to-cart.store');
        });
    });
});