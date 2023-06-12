<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\OnepageController;

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {
    /**
     * Cart routes.
     */
    Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
        Route::get('', 'index')->name('shop.checkout.cart.index');

        Route::post('coupon', 'storeCoupon')->name('shop.checkout.cart.coupon.apply');

        Route::delete('coupon', 'destroyCoupon')->name('shop.checkout.cart.coupon.remove');

        /**
         * To Do: Need to remove this end point once all pages completed and need to hit the ajaxified endpoint.
         */
        Route::post('checkout/cart/add/{id}', [CartController::class, 'add'])->name('shop.cart.add');
    });

    /**
     * Checkout routes.
     */
    Route::get('checkout/onepage', [OnepageController::class, 'index'])->defaults('_config', [
        'view' => 'shop::checkout.onepage',
    ])->name('shop.checkout.onepage.index');

    Route::get('checkout/summary', [OnepageController::class, 'summary'])->name('shop.checkout.summary');

    Route::post('checkout/save-address', [OnepageController::class, 'saveAddress'])->name('shop.checkout.save_address');

    Route::post('checkout/save-shipping', [OnepageController::class, 'saveShipping'])->name('shop.checkout.save_shipping');

    Route::post('checkout/save-payment', [OnepageController::class, 'savePayment'])->name('shop.checkout.save_payment');

    Route::post('checkout/check-minimum-order', [OnepageController::class, 'checkMinimumOrder'])->name('shop.checkout.check_minimum_order');

    Route::post('checkout/save-order', [OnepageController::class, 'saveOrder'])->name('shop.checkout.save_order');

    Route::get('checkout/success', [OnepageController::class, 'success'])->name('shop.checkout.success');

    Route::prefix('customer')->group(function () {
        /**
         * For customer exist check.
         */
        Route::post('/customer/exist', [OnepageController::class, 'checkExistCustomer'])->name('shop.customer.checkout.exist');

        /**
         * For customer login checkout.
         */
        Route::post('/customer/checkout/login', [OnepageController::class, 'loginForCheckout'])->name('shop.customer.checkout.login');
    });
});
