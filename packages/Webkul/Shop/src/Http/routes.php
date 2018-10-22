<?php

Route::group(['middleware' => ['web', 'theme', 'locale']], function () {

    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::home.index'
    ])->name('shop.home.index');

    Route::get('/categories/{slug}', 'Webkul\Shop\Http\Controllers\CategoryController@index')->defaults('_config', [
        'view' => 'shop::products.index'
    ]);

    //checkout and cart
    Route::get('checkout/cart', 'Webkul\Shop\Http\Controllers\CartController@index')->defaults('_config', [
        'view' => 'shop::checkout.cart.index'
    ])->name('shop.checkout.cart.index');

    Route::post('checkout/cart/add/{id}', 'Webkul\Shop\Http\Controllers\CartController@add')->name('cart.add');

    Route::get('checkout/cart/addconfigurable/{slug}', 'Webkul\Shop\Http\Controllers\CartController@addconfigurable')->name('cart.add.configurable');

    Route::get('checkout/cart/remove/{id}', 'Webkul\Shop\Http\Controllers\CartController@remove')->name('cart.remove');

    Route::post('/checkout/cart', 'Webkul\Shop\Http\Controllers\CartController@updateBeforeCheckout')->defaults('_config',[
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.update');

    Route::get('/checkout/cart/remove/{id}', 'Webkul\Shop\Http\Controllers\CartController@remove')->defaults('_config',[
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.remove');

    Route::get('/checkout/onepage', 'Webkul\Shop\Http\Controllers\OnepageController@index')->defaults('_config', [
        'view' => 'shop::checkout.onepage'
    ])->name('shop.checkout.onepage.index');

    Route::post('/checkout/save-address', 'Webkul\Shop\Http\Controllers\OnepageController@saveAddress')->name('shop.checkout.save-address');

    Route::post('/checkout/save-shipping', 'Webkul\Shop\Http\Controllers\OnepageController@saveShipping')->name('shop.checkout.save-shipping');

    Route::post('/checkout/save-payment', 'Webkul\Shop\Http\Controllers\OnepageController@savePayment')->name('shop.checkout.save-payment');

    Route::post('/checkout/save-order', 'Webkul\Shop\Http\Controllers\OnepageController@saveOrder')->name('shop.checkout.save-order');

    Route::get('/checkout/success', 'Webkul\Shop\Http\Controllers\OnepageController@success')->defaults('_config', [
        'view' => 'shop::checkout.success'
    ])->name('shop.checkout.success');

    //dummy
    Route::get('test', 'Webkul\Shop\Http\Controllers\CartController@test');

    Route::get('/products/{slug}', 'Webkul\Shop\Http\Controllers\ProductController@index')->defaults('_config', [
        'view' => 'shop::products.view'
    ])->name('shop.products.index');


    // Product Review routes
    Route::get('/reviews/{slug}', 'Webkul\Shop\Http\Controllers\ReviewController@show')->defaults('_config', [
        'view' => 'shop::products.reviews.index'
    ])->name('shop.reviews.index');

    Route::get('/product/{slug}/review', 'Webkul\Shop\Http\Controllers\ReviewController@create')->defaults('_config', [
        'view' => 'shop::products.reviews.create'
    ])->name('shop.reviews.create');

    Route::post('/product/{slug}/review', 'Webkul\Shop\Http\Controllers\ReviewController@store')->defaults('_config', [
        'redirect' => 'customer.reviews.index'
    ])->name('shop.reviews.store');

    // forgot Password Routes
    Route::get('/forgot-password', 'Webkul\Customer\Http\Controllers\ForgotPasswordController@create')->defaults('_config', [
        'view' => 'shop::customers.signup.forgot-password'
    ])->name('customer.forgot-password.create');

    Route::post('/forgot-password', 'Webkul\Customer\Http\Controllers\ForgotPasswordController@store')->name('customer.forgot-password.store');

    //Reset Password create
    Route::get('/reset-password/{token}', 'Webkul\Customer\Http\Controllers\ResetPasswordController@create')->defaults('_config', [
        'view' => 'shop::customers.signup.reset-password'
    ])->name('password.reset');

    Route::post('/reset-password', 'Webkul\Customer\Http\Controllers\ResetPasswordController@store')->defaults('_config', [
        'redirect' => 'customer.session.index'
    ])->name('customer.reset-password.store');

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

            //wishlist
            Route::get('wishlist/add/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@add')->name('customer.wishlist.add');

            Route::get('wishlist/remove/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@remove')->name('customer.wishlist.remove');

            Route::get('wishlist/move/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@move')->name('customer.wishlist.move');

            Route::get('wishlist/moveall', 'Webkul\Customer\Http\Controllers\WishlistController@moveAll')->name('customer.wishlist.moveall');

            //customer account
            Route::prefix('account')->group(function () {

                Route::get('index', 'Webkul\Customer\Http\Controllers\AccountController@index')->defaults('_config', [
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
                    'view' => 'shop::customers.account.address.address',
                    'redirect' => 'customer.address.index'
                ])->name('customer.address.create');

                Route::get('address/edit', 'Webkul\Customer\Http\Controllers\AddressController@showEdit')->defaults('_config', [
                    'view' => 'shop::customers.account.address.edit'
                ])->name('customer.address.edit');

                Route::post('address/edit', 'Webkul\Customer\Http\Controllers\AddressController@edit')->defaults('_config', [
                    'redirect' => 'customer.address.index'
                ])->name('customer.address.edit');
                /*    Routes for Addresses ends here   */

                /* Wishlist route */
                Route::get('wishlist', 'Webkul\Customer\Http\Controllers\WishlistController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.wishlist.wishlist'
                ])->name('customer.wishlist.index');

                /* Orders route */
                Route::get('orders', 'Webkul\Shop\Http\Controllers\OrderController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.orders.index'
                ])->name('customer.orders.index');

                Route::get('orders/view/{id}', 'Webkul\Shop\Http\Controllers\OrderController@view')->defaults('_config', [
                    'view' => 'shop::customers.account.orders.view'
                ])->name('customer.orders.view');

                /* Reviews route */
                Route::get('reviews', 'Webkul\Customer\Http\Controllers\CustomerController@reviews')->defaults('_config', [
                    'view' => 'shop::customers.account.reviews.index'
                ])->name('customer.reviews.index');
            });
        });
    });
    //customer routes end here
});
