<?php

Route::group(['middleware' => ['web']], function () {
    
    Route::prefix('admin')->group(function () {
        Route::get('/login', 'Webkul\User\Http\Controllers\SeesionController@create')->defaults('_config', [
            'view' => 'admin::sessions.create'
        ])->name('admin.session.create');

        Route::post('/login', 'Webkul\User\Http\Controllers\SeesionController@store')->defaults('_config', [
            'redirect' => 'admin/dashboard'
        ])->name('admin.session.store');

        Route::group(['middleware' => ['admin']], function () {
            Route::get('/logout', 'Webkul\User\Http\Controllers\SeesionController@destroy')->defaults('_config', [
                'redirect' => 'admin/login'
            ])->name('admin.session.destroy');
            
            Route::get('/dashboard', 'Webkul\Admin\Http\Controllers\DashboardController@index')->name('admin.dashboard.index');

            Route::get('/users', 'Webkul\User\Http\Controllers\UserController@index')->defaults('_config', [
                'view' => 'admin::users.index'
            ])->name('admin.users.index');

            Route::get('/account', 'Webkul\User\Http\Controllers\AccountController@edit')->defaults('_config', [
                'view' => 'admin::account.edit'
            ])->name('admin.account.edit');
        
            Route::put('/account', 'Webkul\User\Http\Controllers\AccountController@update')->name('admin.account.update');

            Route::get('/permissions', 'Webkul\User\Http\Controllers\PermissionController@index')->defaults('_config', [
                'view' => 'admin::permissions.index'
            ])->name('admin.permissions.index');
        });
    });
});