<?php

// Controllers
use Webkul\CMS\Http\Controllers\Shop\PagePresenterController;
use Webkul\Core\Http\Controllers\CountryStateController;
use Webkul\Customer\Http\Controllers\AccountController;
use Webkul\Customer\Http\Controllers\AddressController;
use Webkul\Customer\Http\Controllers\CustomerController;
use Webkul\Customer\Http\Controllers\ForgotPasswordController;
use Webkul\Customer\Http\Controllers\RegistrationController;
use Webkul\Customer\Http\Controllers\ResetPasswordController;
use Webkul\Customer\Http\Controllers\SessionController;
use Webkul\Customer\Http\Controllers\WishlistController;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\DownloadableProductController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\OnepageController;
use Webkul\Shop\Http\Controllers\OrderController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ReviewController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    /**
     * Store front home.
     */
    Route::get('/', [HomeController::class, 'index'])->defaults('_config', [
        'view' => 'shop::home.index'
    ])->name('shop.home.index');

    /**
     * Store front search.
     */
    Route::get('/search', [SearchController::class, 'index'])->defaults('_config', [
        'view' => 'shop::search.search'
    ])->name('shop.search.index');

    Route::post('/upload-search-image', [HomeController::class, 'upload'])->name('shop.image.search.upload');

    /**
     * Subscription.
     */
    Route::get('/subscribe', [SubscriptionController::class, 'subscribe'])->name('shop.subscribe');

    Route::get('/unsubscribe/{token}', [SubscriptionController::class, 'unsubscribe'])->name('shop.unsubscribe');

    /**
     * Country-State selector.
     */
    Route::get('get/countries', [CountryStateController::class, 'getCountries'])->defaults('_config', [
        'view' => 'shop::test'
    ])->name('get.countries');

    /**
     * Get States when Country is passed.
     */
    Route::get('get/states/{country}', [CountryStateController::class, 'getStates'])->defaults('_config', [
        'view' => 'shop::test'
    ])->name('get.states');

    /**
     * Cart, coupons and checkout.
     */
    // Cart items listing.
    Route::get('checkout/cart', [CartController::class, 'index'])->defaults('_config', [
        'view' => 'shop::checkout.cart.index'
    ])->name('shop.checkout.cart.index');

    // Add cart items.
    Route::post('checkout/cart/add/{id}', [CartController::class, 'add'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('cart.add');

    // Cart items remove.
    Route::get('checkout/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Cart update before checkout.
    Route::post('/checkout/cart', [CartController::class, 'updateBeforeCheckout'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.update');

    // Cart items remove.
    Route::get('/checkout/cart/remove/{id}', [CartController::class, 'remove'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.remove');

    // Move item to wishlist from cart.
    Route::post('move/wishlist/{id}', [CartController::class, 'moveToWishlist'])->name('shop.movetowishlist');

    // Apply coupons.
    Route::post('checkout/cart/coupon', [CartController::class, 'applyCoupon'])->name('shop.checkout.cart.coupon.apply');

    // Remove coupons.
    Route::delete('checkout/cart/coupon', [CartController::class, 'removeCoupon'])->name('shop.checkout.coupon.remove.coupon');

    // Checkout index page.
    Route::get('/checkout/onepage', [OnepageController::class, 'index'])->defaults('_config', [
        'view' => 'shop::checkout.onepage'
    ])->name('shop.checkout.onepage.index');

    // Checkout get summary.
    Route::get('/checkout/summary',[OnepageController::class, 'summary'])->name('shop.checkout.summary');

    // Checkout save address form.
    Route::post('/checkout/save-address', [OnepageController::class, 'saveAddress'])->name('shop.checkout.save-address');

    // Checkout save shipping address form.
    Route::post('/checkout/save-shipping', [OnepageController::class, 'saveShipping'])->name('shop.checkout.save-shipping');

    // Checkout save payment method form.
    Route::post('/checkout/save-payment', [OnepageController::class, 'savePayment'])->name('shop.checkout.save-payment');

    // Check minimum order.
    Route::post('/checkout/check-minimum-order', [OnepageController::class, 'checkMinimumOrder'])->name('shop.checkout.check-minimum-order');

    // Checkout save order.
    Route::post('/checkout/save-order', [OnepageController::class, 'saveOrder'])->name('shop.checkout.save-order');

    // Checkout order successful.
    Route::get('/checkout/success', [OnepageController::class, 'success'])->defaults('_config', [
        'view' => 'shop::checkout.success'
    ])->name('shop.checkout.success');

    /**
     * Product reviews.
     */
    // Show product review form.
    Route::get('/reviews/{slug}', [ReviewController::class, 'show'])->defaults('_config', [
        'view' => 'shop::products.reviews.index'
    ])->name('shop.reviews.index');

    // Show product review listing.
    Route::get('/product/{slug}/review', [ReviewController::class, 'create'])->defaults('_config', [
        'view' => 'shop::products.reviews.create'
    ])->name('shop.reviews.create');

    // Store product review.
    Route::post('/product/{slug}/review', [ReviewController::class, 'store'])->defaults('_config', [
        'redirect' => 'shop.home.index'
    ])->name('shop.reviews.store');

    /**
     * Downloadable products.
     */
    Route::get('/downloadable/download-sample/{type}/{id}', [ProductController::class, 'downloadSample'])->name('shop.downloadable.download_sample');

    Route::get('/product/{id}/{attribute_id}', [ProductController::class, 'download'])->defaults('_config', [
        'view' => 'shop.products.index'
    ])->name('shop.product.file.download');

    /**
     * These are the routes which are used during checkout for checking the customer.
     * So, placed outside the cart merger middleware.
     */
    Route::prefix('customer')->group(function () {
        // For customer exist check.
        Route::post('/customer/exist', [OnepageController::class, 'checkExistCustomer'])->name('customer.checkout.exist');

        // For customer login checkout.
        Route::post('/customer/checkout/login', [OnepageController::class, 'loginForCheckout'])->name('customer.checkout.login');
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
            Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->defaults('_config', [
                'view' => 'shop::customers.signup.forgot-password'
            ])->name('customer.forgot-password.create');

            // Store forgot password.
            Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('customer.forgot-password.store');

            // Reset password form.
            Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->defaults('_config', [
                'view' => 'shop::customers.signup.reset-password'
            ])->name('customer.reset-password.create');

            // Store reset password.
            Route::post('/reset-password',[ResetPasswordController::class, 'store'])->defaults('_config', [
                'redirect' => 'customer.profile.index'
            ])->name('customer.reset-password.store');

            /**
             * Login routes.
             */
            // Login form.
            Route::get('login', [SessionController::class, 'show'])->defaults('_config', [
                'view' => 'shop::customers.session.index',
            ])->name('customer.session.index');

            // Login.
            Route::post('login', [SessionController::class, 'create'])->defaults('_config', [
                'redirect' => 'customer.profile.index'
            ])->name('customer.session.create');

            /**
             * Registration routes.
             */
            // Show registration form.
            Route::get('register', [RegistrationController::class, 'show'])->defaults('_config', [
                'view' => 'shop::customers.signup.index'
            ])->name('customer.register.index');

            // Store new registered user.
            Route::post('register', [RegistrationController::class, 'create'])->defaults('_config', [
                'redirect' => 'customer.session.index',
            ])->name('customer.register.create');

            // Verify account.
            Route::get('/verify-account/{token}', [RegistrationController::class, 'verifyAccount'])->name('customer.verify');

            // Resend verification email.
            Route::get('/resend/verification/{email}', [RegistrationController::class, 'resendVerificationEmail'])->name('customer.resend.verification-email');

            /**
             * Customer authented routes. All the below routes only be accessible
             * if customer is authenticated.
             */
            Route::group(['middleware' => ['customer']], function () {
                /**
                 * Logout.
                 */
                Route::get('logout', [SessionController::class, 'destroy'])->defaults('_config', [
                    'redirect' => 'customer.session.index'
                ])->name('customer.session.destroy');

                /**
                 * Wishlist.
                 */
                Route::post('wishlist/add/{id}', [WishlistController::class, 'add'])->name('customer.wishlist.add');

                Route::delete('wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('customer.wishlist.remove');

                Route::delete('wishlist/removeall', [WishlistController::class, 'removeAll'])->name('customer.wishlist.removeall');

                Route::get('wishlist/move/{id}', [WishlistController::class, 'move'])->name('customer.wishlist.move');

                /**
                 * Customer account. All the below routes are related to
                 * customer account details.
                 */
                Route::prefix('account')->group(function () {
                    /**
                     * Dashboard.
                     */
                    Route::get('index', [AccountController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.index'
                    ])->name('customer.account.index');

                    /**
                     * Profile.
                     */
                    Route::get('profile', [CustomerController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.index'
                    ])->name('customer.profile.index');

                    Route::get('profile/edit', [CustomerController::class, 'edit'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.edit'
                    ])->name('customer.profile.edit');

                    Route::post('profile/edit', [CustomerController::class, 'update'])->defaults('_config', [
                        'redirect' => 'customer.profile.index'
                    ])->name('customer.profile.store');

                    Route::post('profile/destroy', [CustomerController::class, 'destroy'])->defaults('_config', [
                        'redirect' => 'customer.profile.index'
                    ])->name('customer.profile.destroy');

                    /**
                     * Addresses.
                     */
                    Route::get('addresses', [AddressController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.index'
                    ])->name('customer.address.index');

                    Route::get('addresses/create', [AddressController::class, 'create'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.create'
                    ])->name('customer.address.create');

                    Route::post('addresses/create', [AddressController::class, 'store'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.address',
                        'redirect' => 'customer.address.index'
                    ])->name('customer.address.store');

                    Route::get('addresses/edit/{id}', [AddressController::class, 'edit'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.edit'
                    ])->name('customer.address.edit');

                    Route::put('addresses/edit/{id}', [AddressController::class, 'update'])->defaults('_config', [
                        'redirect' => 'customer.address.index'
                    ])->name('customer.address.update');

                    Route::get('addresses/default/{id}', [AddressController::class, 'makeDefault'])->name('make.default.address');

                    Route::delete('addresses/delete/{id}', [AddressController::class, 'destroy'])->name('address.delete');

                    /**
                     * Wishlist.
                     */
                    Route::get('wishlist', [WishlistController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.wishlist.wishlist'
                    ])->name('customer.wishlist.index');

                    /**
                     * Orders.
                     */
                    Route::get('orders', [OrderController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.orders.index'
                    ])->name('customer.orders.index');

                    Route::get('orders/view/{id}', [OrderController::class, 'view'])->defaults('_config', [
                        'view' => 'shop::customers.account.orders.view'
                    ])->name('customer.orders.view');

                    Route::get('orders/print/{id}', [OrderController::class, 'print'])->defaults('_config', [
                        'view' => 'shop::customers.account.orders.print'
                    ])->name('customer.orders.print');

                    Route::post('/orders/cancel/{id}', [OrderController::class, 'cancel'])->name('customer.orders.cancel');

                    /**
                     * Downloadable products.
                     */
                    Route::get('downloadable-products', [DownloadableProductController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.downloadable_products.index'
                    ])->name('customer.downloadable_products.index');

                    Route::get('downloadable-products/download/{id}', [DownloadableProductController::class, 'download'])->defaults('_config', [
                        'view' => 'shop::customers.account.downloadable_products.index'
                    ])->name('customer.downloadable_products.download');

                    /**
                     * Reviews.
                     */
                    Route::get('reviews', [CustomerController::class, 'reviews'])->defaults('_config', [
                        'view' => 'shop::customers.account.reviews.index'
                    ])->name('customer.reviews.index');

                    Route::delete('reviews/delete/{id}', [ReviewController::class, 'destroy'])->defaults('_config', [
                        'redirect' => 'customer.reviews.index'
                    ])->name('customer.review.delete');

                    Route::delete('reviews/all-delete', [ReviewController::class, 'deleteAll'])->defaults('_config', [
                        'redirect' => 'customer.reviews.index'
                    ])->name('customer.review.deleteall');
                });
            });
        });

        /**
         * CMS pages.
         */
        Route::get('page/{slug}', [PagePresenterController::class, 'presenter'])->name('shop.cms.page');

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
