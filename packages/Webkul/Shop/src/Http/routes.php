<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::home.index'
    ]);

    Route::get('/categories/{slug}', 'Webkul\Shop\Http\Controllers\CategoryController@index')->defaults('_config', [
        'view' => 'shop::products.index'
    ]);
    
    Route::view('/customer/order','shop::customers.account.orders.index');

    Route::view('/customer/checkout','shop::customers.checkout.index');

    Route::view('/customer/signin','shop::customers.checkout.signin');

    Route::view('/customer/ship_method','shop::customers.checkout.ship-method');

    Route::view('/customer/payment_method','shop::customers.checkout.payment-method');

    Route::view('/customer/payment_complete','shop::customers.checkout.complete');


    Route::view('/cart', 'shop::store.product.view.cart.index');

    Route::view('/products/{slug}', 'shop::store.product.details.index');

    //customer routes starts here
    Route::prefix('customer')->group(function () {

        // Login Routes
        Route::get('login', 'Webkul\Customer\Http\Controllers\SessionController@show')->defaults('_config', [
            'view' => 'shop::customers.session.index',
        ])->name('customer.session.index');

        Route::post('login', 'Webkul\Customer\Http\Controllers\SessionController@create')->defaults('_config', [
            'redirect' => 'customer.account.profile'
        ])->name('customer.session.create');


        // Registration Routes
        Route::get('register', 'Webkul\Customer\Http\Controllers\RegistrationController@show')->defaults('_config', [
            'view' => 'shop::customers.signup.index' //hint path
        ])->name('customer.register.index');

        Route::post('register', 'Webkul\Customer\Http\Controllers\RegistrationController@create')->defaults('_config', [
            'redirect' => 'customer.account.profile',
        ])->name('customer.register.create');   //redirect attribute will get changed immediately to account.index when account's index page will be made

        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {

            //route for logout which will be under the auth guard of the customer by default
            Route::get('logout', 'Webkul\Customer\Http\Controllers\SessionController@destroy')->defaults('_config', [
                'redirect' => 'customer.session.index'
            ])->name('customer.session.destroy');

            //customer account
            Route::prefix('account')->group(function () {
                Route::get('profile', 'Webkul\Customer\Http\Controllers\CustomerController@profile')->defaults('_config', [
                'view' => 'shop::customers.account.profile.index'
                ])->name('customer.account.profile');

                //profile edit
                Route::get('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@editProfile')->defaults('_config', [
                    'view' => 'shop::customers.account.profile.edit'
                ])->name('customer.profile.edit');
            });
        });
    });
    //customer routes end here

});
