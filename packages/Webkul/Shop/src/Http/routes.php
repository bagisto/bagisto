<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
            'view' => 'shop::store.home.index'
        ]);
});

Route::group(['middleware' => ['web']], function () {
    Route::get('/foo', 'Webkul\Shop\Http\Controllers\HomeController@index1');
});
