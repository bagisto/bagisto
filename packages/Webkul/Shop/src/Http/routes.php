<?php

Route::group(['middleware' => ['web']], function () {
     Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
            'view' => 'shop::home.index'
        ]);
});

Route::group(['middleware' => ['web']], function () {
     Route::get('/foo', 'Webkul\Shop\Http\Controllers\HomeController@index1');
});

