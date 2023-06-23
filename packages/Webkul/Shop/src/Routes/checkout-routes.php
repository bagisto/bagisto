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

    Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
        Route::get('', 'index')->name('shop.checkout.onepage.index');

        Route::get('success', 'success')->name('shop.checkout.onepage.success');
    });
});
