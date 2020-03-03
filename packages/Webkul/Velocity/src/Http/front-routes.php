<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::namespace('Webkul\Velocity\Http\Controllers\Shop')->group(function () {
        Route::get('/product-details/{slug}', 'ShopController@fetchProductDetails')
        ->name('velocity.shop.product');

        Route::get('/categorysearch', 'ShopController@search')->defaults('_config', [
            'view' => 'shop::search.search'
        ])->name('velocity.search.index');

        Route::get('/categories', 'ShopController@fetchCategories')->name('velocity.categoriest');

        Route::get('/category-details', 'ShopController@categoryDetails')->name('velocity.category.details');

        Route::get('/fancy-category-details/{slug}', 'ShopController@fetchFancyCategoryDetails')->name('velocity.fancy.category.details');

        Route::get('/mini-cart', 'CartController@getMiniCartDetails')->name('velocity.cart.get.details');

        Route::post('/cart/add', 'CartController@addProductToCart')->name('velocity.cart.add.product');

        Route::delete('/cart/remove/{id}', 'CartController@removeProductFromCart')->name('velocity.cart.remove.product');

        Route::get('/comparison', 'ComparisonController@getComparisonList')
            ->name('velocity.product.compare')
            ->defaults('_config', [
                'view' => 'shop::compare.index'
            ]);

        Route::put('/comparison', 'ComparisonController@addCompareProduct')->name('customer.product.add.compare');

        Route::delete('/comparison', 'ComparisonController@deleteComparisonProduct')->name('customer.product.delete.compare');
    });
});