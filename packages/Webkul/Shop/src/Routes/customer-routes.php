<?php

use Illuminate\Support\Facades\Route;
use Webkul\Customer\Http\Controllers\AccountController;
use Webkul\Customer\Http\Controllers\AddressController;
use Webkul\Customer\Http\Controllers\CustomerController;
use Webkul\Customer\Http\Controllers\ForgotPasswordController;
use Webkul\Customer\Http\Controllers\RegistrationController;
use Webkul\Customer\Http\Controllers\ResetPasswordController;
use Webkul\Customer\Http\Controllers\SessionController;
use Webkul\Customer\Http\Controllers\WishlistController;
use Webkul\Shop\Http\Controllers\DownloadableProductController;
use Webkul\Shop\Http\Controllers\OrderController;
use Webkul\Shop\Http\Controllers\ReviewController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
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
        Route::prefix('customer')->group(function () {
            /**
             * Forgot password routes.
             */
            Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->defaults('_config', [
                'view' => 'shop::customers.signup.forgot-password',
            ])->name('customer.forgot-password.create');

            Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('customer.forgot-password.store');

            Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->defaults('_config', [
                'view' => 'shop::customers.signup.reset-password',
            ])->name('customer.reset-password.create');

            Route::post('/reset-password', [ResetPasswordController::class, 'store'])->defaults('_config', [
                'redirect' => 'customer.profile.index',
            ])->name('customer.reset-password.store');

            /**
             * Login routes.
             */
            Route::get('login', [SessionController::class, 'show'])->defaults('_config', [
                'view' => 'shop::customers.session.index',
            ])->name('customer.session.index');

            Route::post('login', [SessionController::class, 'create'])->defaults('_config', [
                'redirect' => 'customer.profile.index',
            ])->name('customer.session.create');

            /**
             * Registration routes.
             */
            Route::get('register', [RegistrationController::class, 'show'])->defaults('_config', [
                'view' => 'shop::customers.signup.index',
            ])->name('customer.register.index');

            Route::post('register', [RegistrationController::class, 'create'])->defaults('_config', [
                'redirect' => 'customer.session.index',
            ])->name('customer.register.create');

            /**
             * Customer verification routes.
             */
            Route::get('/verify-account/{token}', [RegistrationController::class, 'verifyAccount'])->name('customer.verify');

            Route::get('/resend/verification/{email}', [RegistrationController::class, 'resendVerificationEmail'])->name('customer.resend.verification-email');

            /**
             * Customer authenticated routes. All the below routes only be accessible
             * if customer is authenticated.
             */
            Route::group(['middleware' => ['customer']], function () {
                /**
                 * Logout.
                 */
                Route::delete('logout', [SessionController::class, 'destroy'])->defaults('_config', [
                    'redirect' => 'customer.session.index',
                ])->name('customer.session.destroy');

                /**
                 * Wishlist.
                 */
                Route::post('wishlist/add/{id}', [WishlistController::class, 'add'])->name('customer.wishlist.add');

                Route::post('wishlist/share', [WishlistController::class, 'share'])->name('customer.wishlist.share');

                Route::get('wishlist/shared', [WishlistController::class, 'shared'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.account.wishlist.wishlist-shared',
                    ])
                    ->withoutMiddleware('customer')
                    ->name('customer.wishlist.shared');

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
                        'view' => 'shop::customers.account.index',
                    ])->name('customer.account.index');

                    /**
                     * Profile.
                     */
                    Route::get('profile', [CustomerController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.index',
                    ])->name('customer.profile.index');

                    Route::get('profile/edit', [CustomerController::class, 'edit'])->defaults('_config', [
                        'view' => 'shop::customers.account.profile.edit',
                    ])->name('customer.profile.edit');

                    Route::post('profile/edit', [CustomerController::class, 'update'])->defaults('_config', [
                        'redirect' => 'customer.profile.index',
                    ])->name('customer.profile.store');

                    Route::post('profile/destroy', [CustomerController::class, 'destroy'])->defaults('_config', [
                        'redirect' => 'customer.profile.index',
                    ])->name('customer.profile.destroy');

                    /**
                     * Addresses.
                     */
                    Route::get('addresses', [AddressController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.index',
                    ])->name('customer.address.index');

                    Route::get('addresses/create', [AddressController::class, 'create'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.create',
                    ])->name('customer.address.create');

                    Route::post('addresses/create', [AddressController::class, 'store'])->defaults('_config', [
                        'view'     => 'shop::customers.account.address.address',
                        'redirect' => 'customer.address.index',
                    ])->name('customer.address.store');

                    Route::get('addresses/edit/{id}', [AddressController::class, 'edit'])->defaults('_config', [
                        'view' => 'shop::customers.account.address.edit',
                    ])->name('customer.address.edit');

                    Route::put('addresses/edit/{id}', [AddressController::class, 'update'])->defaults('_config', [
                        'redirect' => 'customer.address.index',
                    ])->name('customer.address.update');

                    Route::get('addresses/default/{id}', [AddressController::class, 'makeDefault'])->name('make.default.address');

                    Route::delete('addresses/delete/{id}', [AddressController::class, 'destroy'])->name('address.delete');

                    /**
                     * Wishlist.
                     */
                    Route::get('wishlist', [WishlistController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.wishlist.wishlist',
                    ])->name('customer.wishlist.index');

                    /**
                     * Orders.
                     */
                    Route::get('orders', [OrderController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.orders.index',
                    ])->name('customer.orders.index');

                    Route::get('orders/view/{id}', [OrderController::class, 'view'])->defaults('_config', [
                        'view' => 'shop::customers.account.orders.view',
                    ])->name('customer.orders.view');

                    Route::get('orders/print/{id}', [OrderController::class, 'printInvoice'])->defaults('_config', [
                        'view' => 'shop::customers.account.orders.print',
                    ])->name('customer.orders.print');

                    Route::post('/orders/cancel/{id}', [OrderController::class, 'cancel'])->name('customer.orders.cancel');

                    /**
                     * Downloadable products.
                     */
                    Route::get('downloadable-products', [DownloadableProductController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.downloadable_products.index',
                    ])->name('customer.downloadable_products.index');

                    Route::get('downloadable-products/download/{id}', [DownloadableProductController::class, 'download'])->defaults('_config', [
                        'view' => 'shop::customers.account.downloadable_products.index',
                    ])->name('customer.downloadable_products.download');

                    /**
                     * Reviews.
                     */
                    Route::get('reviews', [CustomerController::class, 'reviews'])->defaults('_config', [
                        'view' => 'shop::customers.account.reviews.index',
                    ])->name('customer.reviews.index');

                    Route::delete('reviews/delete/{id}', [ReviewController::class, 'destroy'])->defaults('_config', [
                        'redirect' => 'customer.reviews.index',
                    ])->name('customer.review.delete');

                    Route::delete('reviews/all-delete', [ReviewController::class, 'deleteAll'])->defaults('_config', [
                        'redirect' => 'customer.reviews.index',
                    ])->name('customer.review.deleteall');
                });
            });
        });
    });
});
