<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\Customer\Account\AddressController;
use Webkul\Shop\Http\Controllers\Customer\Account\DownloadableProductController;
use Webkul\Shop\Http\Controllers\Customer\Account\OrderController;
use Webkul\Shop\Http\Controllers\Customer\Account\WishlistController;
use Webkul\Shop\Http\Controllers\Customer\CustomerController;
use Webkul\Shop\Http\Controllers\Customer\ForgotPasswordController;
use Webkul\Shop\Http\Controllers\Customer\RegistrationController;
use Webkul\Shop\Http\Controllers\Customer\ResetPasswordController;
use Webkul\Shop\Http\Controllers\Customer\SessionController;
use Webkul\Shop\Http\Controllers\DataGridController;

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {

    Route::prefix('customer')->group(function () {
        /**
         * Forgot password routes.
         */
        Route::controller(ForgotPasswordController::class)->prefix('forgot-password')->group(function () {
            Route::get('', 'create')->name('shop.customers.forgot_password.create');

            Route::post('', 'store')->name('shop.customers.forgot_password.store');
        });

        /**
         * Reset password routes.
         */
        Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
            Route::get('{token}', 'create')->name('shop.customers.reset_password.create');

            Route::post('', 'store')->name('shop.customers.reset_password.store');
        });

        /**
         * Login routes.
         */
        Route::controller(SessionController::class)->prefix('login')->group(function () {
            Route::get('', 'index')->name('shop.customer.session.index');

            Route::post('', 'store')->name('shop.customer.session.create');
        });

        /**
         * Registration routes.
         */
        Route::controller(RegistrationController::class)->group(function () {
            Route::prefix('register')->group(function () {
                Route::get('', 'index')->name('shop.customers.register.index');

                Route::post('', 'store')->name('shop.customers.register.store');
            });

            /**
             * Customer verification routes.
             */
            Route::get('verify-account/{token}', 'verifyAccount')->name('shop.customers.verify');

            Route::get('resend/verification/{email}', 'resendVerificationEmail')->name('shop.customers.resend.verification_email');
        });

        /**
         * Customer authenticated routes. All the below routes only be accessible
         * if customer is authenticated.
         */
        Route::group(['middleware' => ['customer']], function () {
            /**
             * Datagrid routes.
             */
            Route::get('datagrid/look-up', [DataGridController::class, 'lookUp'])->name('shop.customer.datagrid.look_up');

            /**
             * Logout.
             */
            Route::delete('logout', [SessionController::class, 'destroy'])->defaults('_config', [
                'redirect' => 'shop.customer.session.index',
            ])->name('shop.customer.session.destroy');

            /**
             * Customer account. All the below routes are related to
             * customer account details.
             */
            Route::prefix('account')->group(function () {
                Route::get('', [CustomerController::class, 'account'])->name('shop.customers.account.index');

                /**
                 * Wishlist.
                 */
                Route::get('wishlist', [WishlistController::class, 'index'])->name('shop.customers.account.wishlist.index');

                /**
                 * Profile.
                 */
                Route::controller(CustomerController::class)->group(function () {
                    Route::prefix('profile')->group(function () {
                        Route::get('', 'index')->name('shop.customers.account.profile.index');

                        Route::get('edit', 'edit')->name('shop.customers.account.profile.edit');

                        Route::post('edit', 'update')->name('shop.customers.account.profile.update');

                        Route::post('destroy', 'destroy')->name('shop.customers.account.profile.destroy');
                    });

                    Route::get('reviews', 'reviews')->name('shop.customers.account.reviews.index');
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
                 * Orders.
                 */
                Route::controller(OrderController::class)->prefix('orders')->group(function () {
                    Route::get('', 'index')->name('shop.customers.account.orders.index');

                    Route::get('view/{id}', 'view')->name('shop.customers.account.orders.view');

                    Route::get('reorder/{id}', 'reorder')->name('shop.customers.account.orders.reorder');

                    Route::post('cancel/{id}', 'cancel')->name('shop.customers.account.orders.cancel');

                    Route::get('print/Invoice/{id}', 'printInvoice')->name('shop.customers.account.orders.print-invoice');
                });

                /**
                 * Downloadable products.
                 */
                Route::controller(DownloadableProductController::class)->prefix('downloadable-products')->group(function () {
                    Route::get('', 'index')->name('shop.customers.account.downloadable_products.index');

                    Route::get('download/{id}', 'download')->name('shop.customers.account.downloadable_products.download');
                });
            });
        });
    });
});
