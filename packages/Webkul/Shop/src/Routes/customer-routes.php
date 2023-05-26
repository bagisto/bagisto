<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\Customer\AccountController;
use Webkul\Shop\Http\Controllers\Customer\AddressController;
use Webkul\Shop\Http\Controllers\Customer\CustomerController;
use Webkul\Shop\Http\Controllers\Customer\ForgotPasswordController;
use Webkul\Shop\Http\Controllers\Customer\RegistrationController;
use Webkul\Shop\Http\Controllers\Customer\ResetPasswordController;
use Webkul\Shop\Http\Controllers\Customer\SessionController;
use Webkul\Shop\Http\Controllers\Customer\WishlistController;
use Webkul\Shop\Http\Controllers\DownloadableProductController;
use Webkul\Shop\Http\Controllers\Customer\OrderController;
use Webkul\Shop\Http\Controllers\ReviewController;

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {
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
            Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('shop.customer.forgot_password.create');

            Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('shop.customer.forgot_password.store');

            /**
             * Reset password routes.
             */
            Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('shop.customer.reset_password.create');

            Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('shop.customer.reset_password.store');

            /**
             * Login routes.
             */
            Route::get('login', [SessionController::class, 'show'])->name('shop.customer.session.index');

            Route::post('login', [SessionController::class, 'create'])->name('shop.customer.session.create');

            /**
             * Registration routes.
             */
            Route::get('register', [RegistrationController::class, 'show'])->name('shop.customer.register.index');

            Route::post('register', [RegistrationController::class, 'create'])->name('shop.customer.register.create');

            /**
             * Customer verification routes.
             */
            Route::get('/verify-account/{token}', [RegistrationController::class, 'verifyAccount'])->name('shop.customer.verify');

            Route::get('/resend/verification/{email}', [RegistrationController::class, 'resendVerificationEmail'])->name('shop.customer.resend.verification_email');

            /**
             * Customer authenticated routes. All the below routes only be accessible
             * if customer is authenticated.
             */
            Route::group(['middleware' => ['customer']], function () {
                /**
                 * Logout.
                 */
                Route::delete('logout', [SessionController::class, 'destroy'])->defaults('_config', [
                    'redirect' => 'shop.customer.session.index',
                ])->name('shop.customer.session.destroy');

                /**
                 * Wishlist.
                 */
                Route::post('wishlist/add/{id}', [WishlistController::class, 'add'])->name('shop.customer.wishlist.add');

                Route::post('wishlist/share', [WishlistController::class, 'share'])->name('shop.customer.wishlist.share');

                Route::get('wishlist/shared', [WishlistController::class, 'shared'])
                    ->defaults('_config', [
                        'view' => 'shop::customers.account.wishlist.wishlist-shared',
                    ])
                    ->withoutMiddleware('customer')
                    ->name('shop.customer.wishlist.shared');

                Route::delete('wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('shop.customer.wishlist.remove');

                Route::delete('wishlist/remove-all', [WishlistController::class, 'removeAll'])->name('shop.customer.wishlist.remove_all');

                Route::get('wishlist/move/{id}', [WishlistController::class, 'move'])->name('shop.customer.wishlist.move');

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
                    ])->name('shop.customer.account.index');

                    /**
                     * Profile.
                     */
                    Route::controller(CustomerController::class)->prefix('profile')->group(function () {
                        Route::get('', 'index')->name('shop.customer.profile.index');

                        Route::get('edit', 'edit')->name('shop.customer.profile.edit');

                        Route::post('edit', 'update')->name('shop.customer.profile.store');

                        Route::post('destroy', 'destroy')->name('shop.customer.profile.destroy');

                        Route::get('reviews', 'reviews')->name('shop.customer.reviews.index');
                    });

                    /**
                     * Addresses.
                     */
                    Route::controller(AddressController::class)->prefix('addresses')->group(function () {
                        Route::get('', 'index')->name('shop.customers.account.addresses.index');
    
                        Route::get('create', 'create')->name('shop.customers.account.addresses.create');
    
                        Route::post('create', 'store')->name('shop.customers.account.addresses.store');
    
                        Route::get('edit/{id}', 'edit')->name('shop.customers.account.addresses.edit');
    
                        Route::put('edit/{id}', 'update')->name('shop.customers.account.addresses.update');
    
                        Route::patch('edit/{id}', 'makeDefault')->name('shop.customers.account.addresses.update.default');
    
                        Route::delete('delete/{id}', 'destroy')->name('shop.customers.account.addresses.delete');
                    });

                    /**
                     * Wishlist.
                     */
                    Route::get('wishlist', [WishlistController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.wishlist.wishlist',
                    ])->name('shop.customer.wishlist.index');

                    /**
                     * Orders.
                     */
                     Route::controller(OrderController::class)->prefix('orders')->group(function () {
                         Route::get('', 'index')->name('shop.customers.account.orders.index');

                         Route::get('view/{id}', 'view')->name('shop.customers.account.orders.view');

                         Route::post('cancel/{id}', 'cancel')->name('shop.customers.account.orders.cancel');

                         Route::get('print/Invoice/{id}', 'printInvoice')->name('shop.customers.account.orders.print-invoice');
                     });

                    /**
                     * Downloadable products.
                     */
                    Route::controller(DownloadableProductController::class)->prefix('downloadable-products')->group(function () {
                        Route::get('', 'index')->name('shop.customer.downloadable_products.index');

                        Route::get('download/{id}', 'download')->name('shop.customer.downloadable_products.download');
                    });


                    /**
                     * Reviews.
                     */
                    Route::delete('reviews/delete/{id}', [ReviewController::class, 'destroy'])->defaults('_config', [
                        'redirect' => 'shop.customer.reviews.index',
                    ])->name('shop.customer.review.delete');

                    Route::delete('reviews/all-delete', [ReviewController::class, 'deleteAll'])->defaults('_config', [
                        'redirect' => 'shop.customer.reviews.index',
                    ])->name('shop.customer.review.delete_all');
                });
            });
        });
    });
});
