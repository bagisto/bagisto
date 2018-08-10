<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::store.home.index'
    ])->name('store.home');

    Route::get('/category', 'Webkul\Shop\Http\Controllers\CategoryController@index')->defaults('_config', [
        'view' => 'shop::store.category.index'
    ]);

});


Route::group(['middleware' => ['web']], function () {
    Route::get('/foo', 'Webkul\Shop\Http\Controllers\HomeController@index1');
});
