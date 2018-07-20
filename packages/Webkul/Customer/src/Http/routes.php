<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('customer')->group(function () {
        // Login Routes
        Route::get('/login', 'Webkul\Customer\Http\Controllers\CustomerController@login')->defaults('_config', [
            'view' => 'customer::login.index'
        ])->name('customer.login');

        Route::get('/register', 'Webkul\Customer\Http\Controllers\CustomerController@signup')->defaults('_config', [
            'view' => 'customer::signup.index'
        ])->name('customer.register');

        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {
            Route::get('/logout', 'Webkul\Customer\Http\Controllers\CustomerController@logout')->defaults('_config', [
                'redirect' => 'customer.session.index'
            ])->name('customer.session.destroy');
        });
    });
});
