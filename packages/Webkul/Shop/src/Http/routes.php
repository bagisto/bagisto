<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    /**
     * Store front home.
     */
    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::home.index'
    ])->name('shop.home.index');

    /**
     * Store front search.
     */
    Route::get('/search', 'Webkul\Shop\Http\Controllers\SearchController@index')->defaults('_config', [
        'view' => 'shop::search.search'
    ])->name('shop.search.index');

    Route::post('/upload-search-image', 'Webkul\Shop\Http\Controllers\HomeController@upload')->name('shop.image.search.upload');

    /**
     * Subscription.
     */
    Route::get('/subscribe', 'Webkul\Shop\Http\Controllers\SubscriptionController@subscribe')->name('shop.subscribe');

    Route::get('/unsubscribe/{token}', 'Webkul\Shop\Http\Controllers\SubscriptionController@unsubscribe')->name('shop.unsubscribe');

    /**
     * Country-State selector.
     */
    Route::get('get/countries', 'Webkul\Core\Http\Controllers\CountryStateController@getCountries')->defaults('_config', [
        'view' => 'shop::test'
    ])->name('get.countries');

    /**
     * Get States when Country is passed.
     */
    Route::get('get/states/{country}', 'Webkul\Core\Http\Controllers\CountryStateController@getStates')->defaults('_config', [
        'view' => 'shop::test'
    ])->name('get.states');

    /**
     * Cart, coupons and checkout.
     */
    // Cart items listing.
    Route::get('checkout/cart', 'Webkul\Shop\Http\Controllers\CartController@index')->defaults('_config', [
        'view' => 'shop::checkout.cart.index'
    ])->name('shop.checkout.cart.index');

    // Add cart items.
    Route::post('checkout/cart/add/{id}', 'Webkul\Shop\Http\Controllers\CartController@add')->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('cart.add');

    // Cart items remove.
    Route::get('checkout/cart/remove/{id}', 'Webkul\Shop\Http\Controllers\CartController@remove')->name('cart.remove');

    // Cart update before checkout.
    Route::post('/checkout/cart', 'Webkul\Shop\Http\Controllers\CartController@updateBeforeCheckout')->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.update');

    // Cart items remove.
    Route::get('/checkout/cart/remove/{id}', 'Webkul\Shop\Http\Controllers\CartController@remove')->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.remove');

    // Move item to wishlist from cart.
    Route::post('move/wishlist/{id}', 'Webkul\Shop\Http\Controllers\CartController@moveToWishlist')->name('shop.movetowishlist');

    // Apply coupons.
    Route::post('checkout/cart/coupon', 'Webkul\Shop\Http\Controllers\CartController@applyCoupon')->name('shop.checkout.cart.coupon.apply');

    // Remove coupons.
    Route::delete('checkout/cart/coupon', 'Webkul\Shop\Http\Controllers\CartController@removeCoupon')->name('shop.checkout.coupon.remove.coupon');

    // Checkout index page.
    Route::get('/checkout/onepage', 'Webkul\Shop\Http\Controllers\OnepageController@index')->defaults('_config', [
        'view' => 'shop::checkout.onepage'
    ])->name('shop.checkout.onepage.index');

    // Checkout get summary.
    Route::get('/checkout/summary', 'Webkul\Shop\Http\Controllers\OnepageController@summary')->name('shop.checkout.summary');

    // Checkout save address form.
    Route::post('/checkout/save-address', 'Webkul\Shop\Http\Controllers\OnepageController@saveAddress')->name('shop.checkout.save-address');

    // Checkout save shipping address form.
    Route::post('/checkout/save-shipping', 'Webkul\Shop\Http\Controllers\OnepageController@saveShipping')->name('shop.checkout.save-shipping');

    // Checkout save payment method form.
    Route::post('/checkout/save-payment', 'Webkul\Shop\Http\Controllers\OnepageController@savePayment')->name('shop.checkout.save-payment');

    // Check minimum order.
    Route::post('/checkout/check-minimum-order', 'Webkul\Shop\Http\Controllers\OnepageController@checkMinimumOrder')->name('shop.checkout.check-minimum-order');

    // Checkout save order.
    Route::post('/checkout/save-order', 'Webkul\Shop\Http\Controllers\OnepageController@saveOrder')->name('shop.checkout.save-order');

    // Checkout order successful.
    Route::get('/checkout/success', 'Webkul\Shop\Http\Controllers\OnepageController@success')->defaults('_config', [
        'view' => 'shop::checkout.success'
    ])->name('shop.checkout.success');

    /**
     * Product reviews.
     */
    // Show product review form.
    Route::get('/reviews/{slug}', 'Webkul\Shop\Http\Controllers\ReviewController@show')->defaults('_config', [
        'view' => 'shop::products.reviews.index'
    ])->name('shop.reviews.index');

    // Show product review listing.
    Route::get('/product/{slug}/review', 'Webkul\Shop\Http\Controllers\ReviewController@create')->defaults('_config', [
        'view' => 'shop::products.reviews.create'
    ])->name('shop.reviews.create');

    // Store product review.
    Route::post('/product/{slug}/review', 'Webkul\Shop\Http\Controllers\ReviewController@store')->defaults('_config', [
        'redirect' => 'shop.home.index'
    ])->name('shop.reviews.store');

    /**
     * Downloadable products.
     */
    Route::get('/downloadable/download-sample/{type}/{id}', 'Webkul\Shop\Http\Controllers\ProductController@downloadSample')->name('shop.downloadable.download_sample');

    Route::get('/product/{id}/{attribute_id}', 'Webkul\Shop\Http\Controllers\ProductController@download')->defaults('_config', [
        'view' => 'shop.products.index'
    ])->name('shop.product.file.download');

    /**
     * These are the routes which are used during checkout for checking the customer.
     * So, placed outside the cart merger middleware.
     */
    Route::prefix('customer')->group(function () {
        // For customer exist check.
        Route::post('/customer/exist', 'Webkul\Shop\Http\Controllers\OnepageController@checkExistCustomer')->name('customer.checkout.exist');

        // For customer login checkout.
        Route::post('/customer/checkout/login', 'Webkul\Shop\Http\Controllers\OnepageController@loginForCheckout')->name('customer.checkout.login');
    });

    /**
     * Cart merger middleware. This middleware will take care of the items
     * which are deactivated at the time of buy now functionality. If somehow
     * user redirects without completing the checkout then this will merge
     * full cart.
     *
     * If some routes are not able to merge the cart, then place the route in this
     * group.
     */
    Route::group(['middleware' => ['cart.merger']], function () {
        /**
         * Customer routes.
         */
        Route::prefix('customer')->group(function () {
            /**
             * Forgot password routes.
             */
            // Show forgot password form.
            Route::get('/forgot-password', 'Webkul\Customer\Http\Controllers\ForgotPasswordController@create')->defaults('_config', [
                'view' => 'shop::customers.signup.forgot-password'
            ])->name('customer.forgot-password.create');

            // Store forgot password.
            Route::post('/forgot-password', 'Webkul\Customer\Http\Controllers\ForgotPasswordController@store')->name('customer.forgot-password.store');

            // Reset password form.
            Route::get('/reset-password/{token}', 'Webkul\Customer\Http\Controllers\ResetPasswordController@create')->defaults('_config', [
                'view' => 'shop::customers.signup.reset-password'
            ])->name('customer.reset-password.create');

            // Store reset password.
            Route::post('/reset-password', 'Webkul\Customer\Http\Controllers\ResetPasswordController@store')->defaults('_config', [
                'redirect' => 'customer.profile.index'
            ])->name('customer.reset-password.store');

            /**
             * Login routes.
             */
            // Login form.
            Route::get('login', 'Webkul\Customer\Http\Controllers\SessionController@show')->defaults('_config', [
                'view' => 'shop::customers.session.index',
            ])->name('customer.session.index');

            // Login.
            Route::post('login', 'Webkul\Customer\Http\Controllers\SessionController@create')->defaults('_config', [
                'redirect' => 'customer.profile.index'
            ])->name('customer.session.create');

            /**
             * Registration routes.
             */
            // Show registration form.
            Route::get('register', 'Webkul\Customer\Http\Controllers\RegistrationController@show')->defaults('_config', [
                'view' => 'shop::customers.signup.index'
            ])->name('customer.register.index');

            // Store new registered user.
            Route::post('register', 'Webkul\Customer\Http\Controllers\RegistrationController@create')->defaults('_config', [
                'redirect' => 'customer.session.index',
            ])->name('customer.register.create');

            // Verify account.
            Route::get('/verify-account/{token}', 'Webkul\Customer\Http\Controllers\RegistrationController@verifyAccount')->name('customer.verify');

            // Resend verification email.
            Route::get('/resend/verification/{email}', 'Webkul\Customer\Http\Controllers\RegistrationController@resendVerificationEmail')->name('customer.resend.verification-email');

            /**
             * Customer authented routes. All the below routes only be accessible
             * if customer is authenticated.
             */
            Route::group(['middleware' => ['customer']], function () {
                /**
                 * Logout.
                 */
                Route::get('logout', 'Webkul\Customer\Http\Controllers\SessionController@destroy')->defaults('_config', [
                    'redirect' => 'customer.session.index'
                ])->name('customer.session.destroy');

                /**
                 * Wishlist.
                 */
                Route::post('wishlist/add/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@add')->name('customer.wishlist.add');

                Route::delete('wishlist/remove/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@remove')->name('customer.wishlist.remove');

                Route::delete('wishlist/removeall', 'Webkul\Customer\Http\Controllers\WishlistController@removeAll')->name('customer.wishlist.removeall');

                Route::get('wishlist/move/{id}', 'Webkul\Customer\Http\Controllers\WishlistController@move')->name('customer.wishlist.move');

                /**
                 * Customer account. All the below routes are related to
                 * customer account details.
                 */
                Route::prefix('account')->group(function () {
                    /**
                     * Dashboard.
                     */
                    Route::get('index', 'Webkul\Customer\Http\Controllers\AccountController@index')->defaults('_config', [
                        'view' => 'shop::customers.account.index'
                    ])->name('customer.account.index');

                    /**
                     * Profile.
                     */
                    Route::get('profile', 'Webkul\Customer\Http\Controllers\CustomerController@index')->defaults('_config', [
                        'view' => 'shop::customers.account.profile.index'
                    ])->name('customer.profile.index');

                    Route::get('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@edit')->defaults('_config', [
                        'view' => 'shop::customers.account.profile.edit'
                    ])->name('customer.profile.edit');

                    Route::post('profile/edit', 'Webkul\Customer\Http\Controllers\CustomerController@update')->defaults('_config', [
                        'redirect' => 'customer.profile.index'
                    ])->name('customer.profile.store');

                    Route::post('profile/destroy', 'Webkul\Customer\Http\Controllers\CustomerController@destroy')->defaults('_config', [
                        'redirect' => 'customer.profile.index'
                    ])->name('customer.profile.destroy');

                    /**
                     * Addresses.
                     */
                    Route::get('addresses', 'Webkul\Customer\Http\Controllers\AddressController@index')->defaults('_config', [
                        'view' => 'shop::customers.account.address.index'
                    ])->name('customer.address.index');

                    Route::get('addresses/create', 'Webkul\Customer\Http\Controllers\AddressController@create')->defaults('_config', [
                        'view' => 'shop::customers.account.address.create'
                    ])->name('customer.address.create');

                    Route::post('addresses/create', 'Webkul\Customer\Http\Controllers\AddressController@store')->defaults('_config', [
                        'view' => 'shop::customers.account.address.address',
                        'redirect' => 'customer.address.index'
                    ])->name('customer.address.store');

                    Route::get('addresses/edit/{id}', 'Webkul\Customer\Http\Controllers\AddressController@edit')->defaults('_config', [
                        'view' => 'shop::customers.account.address.edit'
                    ])->name('customer.address.edit');

                    Route::put('addresses/edit/{id}', 'Webkul\Customer\Http\Controllers\AddressController@update')->defaults('_config', [
                        'redirect' => 'customer.address.index'
                    ])->name('customer.address.update');

                    Route::get('addresses/default/{id}', 'Webkul\Customer\Http\Controllers\AddressController@makeDefault')->name('make.default.address');

                    Route::delete('addresses/delete/{id}', 'Webkul\Customer\Http\Controllers\AddressController@destroy')->name('address.delete');

                    /**
                     * Wishlist.
                     */
                    Route::get('wishlist', 'Webkul\Customer\Http\Controllers\WishlistController@index')->defaults('_config', [
                        'view' => 'shop::customers.account.wishlist.wishlist'
                    ])->name('customer.wishlist.index');

                    /**
                     * Orders.
                     */
                    Route::get('orders', 'Webkul\Shop\Http\Controllers\OrderController@index')->defaults('_config', [
                        'view' => 'shop::customers.account.orders.index'
                    ])->name('customer.orders.index');

                    Route::get('orders/view/{id}', 'Webkul\Shop\Http\Controllers\OrderController@view')->defaults('_config', [
                        'view' => 'shop::customers.account.orders.view'
                    ])->name('customer.orders.view');

                    Route::get('orders/print/{id}', 'Webkul\Shop\Http\Controllers\OrderController@print')->defaults('_config', [
                        'view' => 'shop::customers.account.orders.print'
                    ])->name('customer.orders.print');

                    Route::post('/orders/cancel/{id}', 'Webkul\Shop\Http\Controllers\OrderController@cancel')->name('customer.orders.cancel');

                    /**
                     * Downloadable products.
                     */
                    Route::get('downloadable-products', 'Webkul\Shop\Http\Controllers\DownloadableProductController@index')->defaults('_config', [
                        'view' => 'shop::customers.account.downloadable_products.index'
                    ])->name('customer.downloadable_products.index');

                    Route::get('downloadable-products/download/{id}', 'Webkul\Shop\Http\Controllers\DownloadableProductController@download')->defaults('_config', [
                        'view' => 'shop::customers.account.downloadable_products.index'
                    ])->name('customer.downloadable_products.download');

                    /**
                     * Reviews.
                     */
                    Route::get('reviews', 'Webkul\Customer\Http\Controllers\CustomerController@reviews')->defaults('_config', [
                        'view' => 'shop::customers.account.reviews.index'
                    ])->name('customer.reviews.index');

                    Route::delete('reviews/delete/{id}', 'Webkul\Shop\Http\Controllers\ReviewController@destroy')->defaults('_config', [
                        'redirect' => 'customer.reviews.index'
                    ])->name('customer.review.delete');

                    Route::delete('reviews/all-delete', 'Webkul\Shop\Http\Controllers\ReviewController@deleteAll')->defaults('_config', [
                        'redirect' => 'customer.reviews.index'
                    ])->name('customer.review.deleteall');
                });
            });
        });

        /**
         * CMS pages.
         */
        Route::get('page/{slug}', 'Webkul\CMS\Http\Controllers\Shop\PagePresenterController@presenter')->name('shop.cms.page');

        /**
         * Fallback route.
         */
        Route::fallback(\Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController::class . '@index')
            ->defaults('_config', [
                'product_view' => 'shop::products.view',
                'category_view' => 'shop::products.index'
            ])
            ->name('shop.productOrCategory.index');
    });
});
