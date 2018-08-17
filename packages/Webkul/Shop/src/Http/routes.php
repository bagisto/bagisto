<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::store.home.index'
    ]);

    Route::get('/categories/{slug}', 'Webkul\Shop\Http\Controllers\ProductController@index')->defaults('_config', [
        'view' => 'shop::products.index'
    ]);
});