<?php

use Illuminate\Http\Request;

Route::prefix('api')->group(function () {
    //customer APIs
    Route::prefix('customer')->group(function () {
        Route::post('login', 'Webkul\API\Http\Controllers\Customer\AuthController@create')->name('login');
    });

    //Admin APIs
    Route::prefix('admin')->group(function () {
        Route::post('login', 'Webkul\API\Http\Controllers\Admin\AuthController@create');
    });
});

