<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('customer')->group(function () {

        // Login Routes
        Route::get('/login', 'Webkul\Customer\Http\Controllers\SessionController@show')->defaults('_config', [
            'view' => 'customer::login.index',
        ])->name('customer.session.index');

        Route::post('/login', 'Webkul\Customer\Http\Controllers\SessionController@create')->defaults('_config', [
            'redirect' => 'customer.dashboard.index'
        ])->name('customer.session.create');


        // Registration Routes
        Route::get('/register', 'Webkul\Customer\Http\Controllers\RegistrationController@show')->defaults('_config', [
            'view' => 'customer::signup.index' //hint path
        ])->name('customer.register.index');

        Route::post('/register', 'Webkul\Customer\Http\Controllers\RegistrationController@create')->defaults('_config', [
            'redirect' => 'customer.dashboard.index',
        ])->name('customer.register.create');

        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {

            //route for logout which will be under the auth guard of the customer by default
            Route::get('/logout', 'Webkul\Customer\Http\Controllers\SessionController@logout')->defaults('_config', [
                'redirect' => 'customer.session.index'
            ])->name('customer.session.destroy');

            //customer dashboard
            Route::get('/dashboard', 'Webkul\Customer\Http\Controllers\CustomerController@dashboard')->defaults('_config', [
                'view' => 'customer::dashboard.index'
            ])->name('customer.dashboard.index');
        });
    });
});
