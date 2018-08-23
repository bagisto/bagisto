<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::home.index'
    ])->name('store.home');

    Route::get('/categories/{slug}', 'Webkul\Shop\Http\Controllers\CategoryController@index')->defaults('_config', [
        'view' => 'shop::products.index'
    ]);

    Route::view('/products/{slug}', 'shop::store.product.details.index');

    //customer routes starts here
    Route::prefix('customer')->group(function () {

        // Login Routes
        Route::get('login', 'Webkul\Customer\Http\Controllers\SessionController@show')->defaults('_config', [
            'view' => 'shop::customers.session.index',
        ])->name('customer.session.index');

        Route::post('login', 'Webkul\Customer\Http\Controllers\SessionController@create')->defaults('_config', [
            'redirect' => 'customer.account.index'
        ])->name('customer.session.create');


        // Registration Routes
        Route::get('register', 'Webkul\Customer\Http\Controllers\RegistrationController@show')->defaults('_config', [
            'view' => 'shop::customers.signup.index' //hint path
        ])->name('customer.register.index');

        Route::post('register', 'Webkul\Customer\Http\Controllers\RegistrationController@create')->defaults('_config', [
            'redirect' => 'customer.account.index',
        ])->name('customer.register.create');   //redirect attribute will get changed immediately to account.index when account's index page will be made

        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {

            //route for logout which will be under the auth guard of the customer by default
            Route::get('logout', 'Webkul\Customer\Http\Controllers\SessionController@destroy')->defaults('_config', [
                'redirect' => 'customer.session.index'
            ])->name('customer.session.destroy');

            Route::view('/cart', 'shop::store.product.view.cart.index')->name('customer.cart');

            Route::view('/product', 'shop::store.product.details.home.index')->name('customer.product');

            Route::view('/product/review', 'shop::store.product.review.index')->name('customer.product.review');

            //customer account
            Route::prefix('account')->group(function () {

                Route::get('account/index', 'Webkul\Customer\Http\Controllers\AccountController@index')->defaults('_config', [
                'view' => 'shop::customers.account.index'
                ])->name('customer.account.index');


                /*   Profile Routes Starts Here    */
                Route::get('profile', 'Webkul\Customer\Http\Controllers\CustomerController@index')->defaults('_config', [
                'view' => 'shop::customers.account.profile.index'
                ])->name('customer.profile.index');

                //profile edit
                Route::get('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@editIndex')->defaults('_config', [
                    'view' => 'shop::customers.account.profile.edit'
                ])->name('customer.profile.edit');

                Route::post('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@edit')->defaults('_config', [
                    'view' => 'shop::customers.account.profile.edit'
                ])->name('customer.profile.edit');
                /*  Profile Routes Ends Here  */


                /*    Routes for Addresses   */

                Route::get('address/index', 'Webkul\Customer\Http\Controllers\AddressController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address'
                ])->name('customer.address.index');

                Route::get('address/create', 'Webkul\Customer\Http\Controllers\AddressController@show')->defaults('_config', [
                    'view' => 'shop::customers.account.address.create'
                ])->name('customer.address.create');

                Route::post('address/create', 'Webkul\Customer\Http\Controllers\AddressController@create')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address'
                ])->name('customer.address.create');

                Route::get('address/edit', 'Webkul\Customer\Http\Controllers\AddressController@edit')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address'
                ])->name('customer.address.edit');

                Route::post('address/edit', 'Webkul\Customer\Http\Controllers\AddressController@edit')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address'
                ])->name('customer.address.edit');

                /*    Routes for Addresses ends here   */

                /* Wishlist route */
                Route::get('wishlist', 'Webkul\Customer\Http\Controllers\WishlistController@wishlist')->defaults('_config', [
                    'view' => 'shop::customers.account.wishlist.wishlist'
                ])->name('customer.wishlist.index');

                /* Orders route */
                Route::get('orders', 'Webkul\Customer\Http\Controllers\OrdersController@orders')->defaults('_config', [
                    'view' => 'shop::customers.account.orders.orders'
                ])->name('customer.orders.index');

                /* Reviews route */
                Route::get('reviews', 'Webkul\Customer\Http\Controllers\CustomerController@reviews')->defaults('_config', [
                    'view' => 'shop::customers.account.reviews.reviews'
                ])->name('customer.reviews.index');

            });
        });
    });
    //customer routes end here

});
