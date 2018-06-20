<?php

Route::group(['middleware' => ['web']], function () {
    
    Route::prefix('admin')->group(function () {
        Route::get('/login', 'Webkul\User\Http\Controllers\SeesionController@create')->defaults('_config', [
            'view' => 'admin::sessions.create'
        ])->name('admin.login');

        Route::post('/login', 'Webkul\User\Http\Controllers\SeesionController@store')->defaults('_config', [
            'redirect' => 'admin/dashboard'
        ])->name('admin.dashboard');

        Route::group(['middleware' => ['admin']], function () {
            Route::get('/logout', 'Webkul\User\Http\Controllers\SeesionController@destroy')->defaults('_config', [
                'redirect' => 'admin/login'
            ])->name('admin.logout');
            
            Route::get('/dashboard', 'Webkul\Admin\Http\Controllers\DashboardController@index')->name('admin.dashboard');
        });
    });
});