<?php

Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['theme']], function () {
        Route::namespace('Webkul\Velocity\Http\Controllers\Shop')->group(function () {
            // Content Pages Route
            Route::get('/product-details/{slug}', 'ShopController@fetchProductDetails')->name('velocity.shop.product');
        });

        Route::get('/search', 'ShopController@search')->defaults('_config', [
            'view' => 'velocity::search.search'
        ])->name('shop.search.index');
    });
});