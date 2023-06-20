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
    });

    /**
     * Checkout routes.
     */
    Route::controller(OnepageController::class)->prefix('checkout')->group(function () {
        Route::get('onepage', 'index')->name('shop.checkout.onepage.index');

        Route::get('summary', 'summary')->name('shop.checkout.summary');
    
        Route::post('save-address', 'saveAddress')->name('shop.checkout.save_address');
    
        Route::post('save-shipping', 'saveShipping')->name('shop.checkout.save_shipping');
    
        Route::post('save-payment', 'savePayment')->name('shop.checkout.save_payment');
    
        Route::post('check-minimum-order', 'checkMinimumOrder')->name('shop.checkout.check_minimum_order');
    
        Route::post('save-order', 'saveOrder')->name('shop.checkout.save_order');
    
        Route::get('success', 'success')->name('shop.checkout.success');
    });

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
