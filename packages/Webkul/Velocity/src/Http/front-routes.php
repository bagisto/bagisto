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

        Route::post('/cart/add', 'ShopController@addProductToCart')->name('velocity.cart.add.product');

        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {
            //customer compared products
            Route::prefix('customer/account')->group(function () {
                Route::get('/compare', 'ShopController@getComparedList')
                    ->name('customer.product.compare')
                    ->defaults('_config', [
                        'view' => 'velocity::customers.account.compare.index'
                    ]);

                Route::put('/compare', 'ShopController@addCompareProduct')->name('customer.product.add.compare');

                Route::delete('/compare', 'ShopController@deleteCompareProduct')->name('customer.product.delete.compare');
            });
        });
    });
});