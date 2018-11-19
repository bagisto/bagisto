<?php

Route::group(['middleware' => 'api', 'namespace' => 'Webkul\API\Http\Controllers', 'prefix' => 'api/customer'], function ($router) {
    Route::post('login', 'Customer\AuthController@create');
    Route::post('logout', 'Customer\AuthController@destroy');
    Route::post('refresh', 'Customer\AuthController@refresh');
    Route::post('me', 'Customer\AuthController@me');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers', 'prefix' => 'api/admin'], function ($router) {
    Route::post('login', 'Admin\AuthController@create');
    Route::post('logout', 'Admin\AuthController@destroy');
    Route::post('refresh', 'Admin\AuthController@refresh');
    Route::post('me', 'Admin\AuthController@me');
});