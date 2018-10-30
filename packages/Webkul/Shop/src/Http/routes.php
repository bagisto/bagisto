<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {

    //Store front home
    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::home.index'
    ])->name('shop.home.index');

    //Store front header nav-menu fetch
    Route::get('/categories/{slug}', 'Webkul\Shop\Http\Controllers\CategoryController@index')->defaults('_config', [
        'view' => 'shop::products.index'
    ])->name('shop.categories.index');

    //Store front search
    Route::get('/search', 'Webkul\Shop\Http\Controllers\SearchController@index')->defaults('_config', [
        'view' => 'shop::search.search'
    ])->name('shop.search.index');

    //checkout and cart
    //Cart Items(listing)
    Route::get('checkout/cart', 'Webkul\Shop\Http\Controllers\CartController@index')->defaults('_config', [
        'view' => 'shop::checkout.cart.index'
    ])->name('shop.checkout.cart.index');

    //Cart Items Add
    Route::post('checkout/cart/add/{id}', 'Webkul\Shop\Http\Controllers\CartController@add')->name('cart.add');

    //Cart Items Add Configurable for more
    Route::get('checkout/cart/addconfigurable/{slug}', 'Webkul\Shop\Http\Controllers\CartController@addconfigurable')->name('cart.add.configurable');

    //Cart Items Remove
    Route::get('checkout/cart/remove/{id}', 'Webkul\Shop\Http\Controllers\CartController@remove')->name('cart.remove');

    //Cart Update Before Checkout
    Route::post('/checkout/cart', 'Webkul\Shop\Http\Controllers\CartController@updateBeforeCheckout')->defaults('_config',[
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.update');

    //Cart Items Remove
    Route::get('/checkout/cart/remove/{id}', 'Webkul\Shop\Http\Controllers\CartController@remove')->defaults('_config',[
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.remove');

    //Checkout Index page
    Route::get('/checkout/onepage', 'Webkul\Shop\Http\Controllers\OnepageController@index')->defaults('_config', [
        'view' => 'shop::checkout.onepage'
    ])->name('shop.checkout.onepage.index');

    //Checkout Save Address Form Store
    Route::post('/checkout/save-address', 'Webkul\Shop\Http\Controllers\OnepageController@saveAddress')->name('shop.checkout.save-address');

    //Checkout Save Shipping Address Form Store
    Route::post('/checkout/save-shipping', 'Webkul\Shop\Http\Controllers\OnepageController@saveShipping')->name('shop.checkout.save-shipping');

    //Checkout Save Payment Method Form
    Route::post('/checkout/save-payment', 'Webkul\Shop\Http\Controllers\OnepageController@savePayment')->name('shop.checkout.save-payment');

    //Checkout Save Order
    Route::post('/checkout/save-order', 'Webkul\Shop\Http\Controllers\OnepageController@saveOrder')->name('shop.checkout.save-order');

    //Checkout Order Successfull
    Route::get('/checkout/success', 'Webkul\Shop\Http\Controllers\OnepageController@success')->defaults('_config', [
        'view' => 'shop::checkout.success'
    ])->name('shop.checkout.success');

    //Shop buynow button action
    Route::get('buynow/{id}', 'Webkul\Shop\Http\Controllers\CartController@test')->name('shop.product.buynow');

    //Shop buynow button action
    Route::get('move/cart/{id}', 'Webkul\Shop\Http\Controllers\CartController@moveToWishlist')->name('shop.movetowishlist');

    //Show Product Details Page(For individually Viewable Product)
    Route::get('/products/{slug}', 'Webkul\Shop\Http\Controllers\ProductController@index')->defaults('_config', [
        'view' => 'shop::products.view'
    ])->name('shop.products.index');

    // Show Product Review Form
    Route::get('/reviews/{slug}', 'Webkul\Shop\Http\Controllers\ReviewController@show')->defaults('_config', [
        'view' => 'shop::products.reviews.index'
    ])->name('shop.reviews.index');

    // Show Product Review(listing)
    Route::get('/product/{slug}/review', 'Webkul\Shop\Http\Controllers\ReviewController@create')->defaults('_config', [
        'view' => 'shop::products.reviews.create'
    ])->name('shop.reviews.create');

    // Show Product Review Form Store
    Route::post('/product/{slug}/review', 'Webkul\Shop\Http\Controllers\ReviewController@store')->defaults('_config', [
        'redirect' => 'customer.reviews.index'
    ])->name('shop.reviews.store');

    //customer routes starts here
    Route::prefix('customer')->group(function () {
        // forgot Password Routes
        // Forgot Password Form Show
        Route::get('/forgot-password', 'Webkul\Customer\Http\Controllers\ForgotPasswordController@create')->defaults('_config', [
            'view' => 'shop::customers.signup.forgot-password'
        ])->name('customer.forgot-password.create');

        // Forgot Password Form Store
        Route::post('/forgot-password', 'Webkul\Customer\Http\Controllers\ForgotPasswordController@store')->name('customer.forgot-password.store');

        // Reset Password Form Show
        Route::get('/reset-password/{token}', 'Webkul\Customer\Http\Controllers\ResetPasswordController@create')->defaults('_config', [
            'view' => 'shop::customers.signup.reset-password'
        ])->name('customer.reset-password.create');

        // Reset Password Form Store
        Route::post('/reset-password', 'Webkul\Customer\Http\Controllers\ResetPasswordController@store')->defaults('_config', [
            'redirect' => 'customer.session.index'
        ])->name('customer.reset-password.store');

        // Login Routes
        // Login form show
        Route::get('login', 'Webkul\Customer\Http\Controllers\SessionController@show')->defaults('_config', [
            'view' => 'shop::customers.session.index',
        ])->name('customer.session.index');

        // Login form store
        Route::post('login', 'Webkul\Customer\Http\Controllers\SessionController@create')->defaults('_config', [
            'redirect' => 'customer.profile.index'
        ])->name('customer.session.create');

        // Registration Routes
        //registration form show
        Route::get('register', 'Webkul\Customer\Http\Controllers\RegistrationController@show')->defaults('_config', [
            'view' => 'shop::customers.signup.index'
        ])->name('customer.register.index');

        //registration form store
        Route::post('register', 'Webkul\Customer\Http\Controllers\RegistrationController@create')->defaults('_config', [
            'redirect' => 'customer.account.index',
        ])->name('customer.register.create');

        // Auth Routes
        Route::group(['middleware' => ['customer']], function () {

            //Customer logout
            Route::get('logout', 'Webkul\Customer\Http\Controllers\SessionController@destroy')->defaults('_config', [
                'redirect' => 'customer.session.index'
            ])->name('customer.session.destroy');

            //Customer Wishlist add
            Route::get('wishlist/add/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@add')->name('customer.wishlist.add');

            //Customer Wishlist remove
            Route::get('wishlist/remove/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@remove')->name('customer.wishlist.remove');

            //Customer Wishlist move to cart
            Route::get('wishlist/move/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@move')->name('customer.wishlist.move');

            //Customer Wishlist move all to cart
            Route::get('wishlist/moveall', 'Webkul\Customer\Http\Controllers\WishlistController@moveAll')->name('customer.wishlist.moveall');

            //customer account
            Route::prefix('account')->group(function () {
                //Customer Dashboard Route
                Route::get('index', 'Webkul\Customer\Http\Controllers\AccountController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.index'
                ])->name('customer.account.index');

                //Customer Profile Show
                Route::get('profile', 'Webkul\Customer\Http\Controllers\CustomerController@index')->defaults('_config', [
                'view' => 'shop::customers.account.profile.index'
                ])->name('customer.profile.index');

                //Customer Profile Edit Form Show
                Route::get('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@editIndex')->defaults('_config', [
                    'view' => 'shop::customers.account.profile.edit'
                ])->name('customer.profile.edit');

                //Customer Profile Edit Form Store
                Route::post('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@edit')->defaults('_config', [
                    'view' => 'shop::customers.account.profile.edit'
                ])->name('customer.profile.edit');
                /*  Profile Routes Ends Here  */

                /*    Routes for Addresses   */
                //Customer Address Show
                Route::get('address/index', 'Webkul\Customer\Http\Controllers\AddressController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address'
                ])->name('customer.address.index');

                //Customer Address Create Form Show
                Route::get('address/create', 'Webkul\Customer\Http\Controllers\AddressController@show')->defaults('_config', [
                    'view' => 'shop::customers.account.address.create'
                ])->name('customer.address.create');

                //Customer Address Create Form Store
                Route::post('address/create', 'Webkul\Customer\Http\Controllers\AddressController@create')->defaults('_config', [
                    'view' => 'shop::customers.account.address.address',
                    'redirect' => 'customer.address.index'
                ])->name('customer.address.create');

                //Customer Address Edit Form Show
                Route::get('address/edit/{id}', 'Webkul\Customer\Http\Controllers\AddressController@showEdit')->defaults('_config', [
                    'view' => 'shop::customers.account.address.edit'
                ])->name('customer.address.edit');

                //Customer Address Edit Form Store
                Route::post('address/edit/{id}', 'Webkul\Customer\Http\Controllers\AddressController@edit')->defaults('_config', [
                    'redirect' => 'customer.address.index'
                ])->name('customer.address.edit');

                //Customer Address Make Default
                Route::get('address/default/{id}', 'Webkul\Customer\Http\Controllers\AddressController@makeDefault')->name('make.default.address');

                //Customer Address Delete
                Route::get('address/delete/{id}', 'Webkul\Customer\Http\Controllers\AddressController@destroy')->name('address.delete');

                /* Wishlist route */
                //Customer wishlist(listing)
                Route::get('wishlist', 'Webkul\Customer\Http\Controllers\WishlistController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.wishlist.wishlist'
                ])->name('customer.wishlist.index');

                /* Orders route */
                //Customer orders(listing)
                Route::get('orders', 'Webkul\Shop\Http\Controllers\OrderController@index')->defaults('_config', [
                    'view' => 'shop::customers.account.orders.index'
                ])->name('customer.orders.index');

                //Customer orders view summary and status
                Route::get('orders/view/{id}', 'Webkul\Shop\Http\Controllers\OrderController@view')->defaults('_config', [
                    'view' => 'shop::customers.account.orders.view'
                ])->name('customer.orders.view');

                /* Reviews route */
                //Customer reviews(listing) only approved
                Route::get('reviews', 'Webkul\Customer\Http\Controllers\CustomerController@reviews')->defaults('_config', [
                    'view' => 'shop::customers.account.reviews.index'
                ])->name('customer.reviews.index');

                //Customer review delete
                Route::get('review/delete/{id}', 'Webkul\Shop\Http\Controllers\ReviewController@destroy')->defaults('_config', [
                    'redirect' => 'customer.reviews.index'
                ])->name('customer.review.delete');
            });
        });
    });
    //customer routes end here
});
