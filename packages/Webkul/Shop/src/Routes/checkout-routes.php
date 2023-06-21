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
    Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
        Route::get('', 'index')->name('shop.checkout.onepage.index');

        Route::get('summary', 'summary')->name('shop.checkout.onepage.summary');
    
        Route::post('addresses', 'storeAddress')->name('shop.checkout.onepage.addresses.store');
    
        Route::post('shipping-methods', 'storeShippingMethod')->name('shop.checkout.onepage.shipping_methods.store');
    
        Route::post('payment-methods', 'storePaymentMethod')->name('shop.checkout.onepage.payment_methods.store');
    
        Route::post('check-minimum-order', 'checkMinimumOrder')->name('shop.checkout.onepage.check_minimum_order');
    
        Route::post('orders', 'storeOrder')->name('shop.checkout.onepage.orders.store');
    
        Route::get('success', 'success')->name('shop.checkout.onepage.success');
    });
});
