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

            // User Routes
            Route::get('/users', 'Webkul\User\Http\Controllers\UserController@index')->defaults('_config', [
                'view' => 'admin::users.index'
            ])->name('admin.users.index');

            Route::get('/users/create', 'Webkul\User\Http\Controllers\UserController@create')->defaults('_config', [
                'view' => 'admin::users.create'
            ])->name('admin.users.create');

            Route::post('/users/create', 'Webkul\User\Http\Controllers\UserController@store')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.store');

            Route::get('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@edit')->defaults('_config', [
                'view' => 'admin::users.edit'
            ])->name('admin.users.edit');

            Route::put('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@update')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.update');

            // User Role Routes
            Route::get('/roles', 'Webkul\User\Http\Controllers\RoleController@index')->defaults('_config', [
                'view' => 'admin::roles.index'
            ])->name('admin.roles.index');
            
            Route::get('/roles/create', 'Webkul\User\Http\Controllers\RoleController@create')->defaults('_config', [
                'view' => 'admin::roles.create'
            ])->name('admin.roles.create');

            Route::post('/roles/create', 'Webkul\User\Http\Controllers\RoleController@store')->defaults('_config', [
                'redirect' => 'admin.roles.index'
            ])->name('admin.roles.store');

            Route::get('/roles/edit/{id}', 'Webkul\User\Http\Controllers\RoleController@edit')->defaults('_config', [
                'view' => 'admin::roles.edit'
            ])->name('admin.roles.edit');

            Route::put('/roles/edit/{id}', 'Webkul\User\Http\Controllers\RoleController@update')->defaults('_config', [
                'redirect' => 'admin.roles.index'
            ])->name('admin.roles.update');


            // Admin Profile route
            Route::get('/account', 'Webkul\User\Http\Controllers\AccountController@edit')->defaults('_config', [
                'view' => 'admin::account.edit'
            ])->name('admin.account.edit');
        
            Route::put('/account', 'Webkul\User\Http\Controllers\AccountController@update')->name('admin.account.update');
        });
    });
});