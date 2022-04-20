<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Controllers\CountryStateController;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\OnepageController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
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
     * Cart routes.
     */
    Route::get('checkout/cart', [CartController::class, 'index'])->defaults('_config', [
        'view' => 'shop::checkout.cart.index'
    ])->name('shop.checkout.cart.index');

    Route::post('checkout/cart/add/{id}', [CartController::class, 'add'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('cart.add');

    Route::get('checkout/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('checkout/cart/remove}', [CartController::class, 'removeAllItems'])->name('cart.remove.all.items');

    Route::post('/checkout/cart', [CartController::class, 'updateBeforeCheckout'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.update');

    Route::get('/checkout/cart/remove/{id}', [CartController::class, 'remove'])->defaults('_config', [
        'redirect' => 'shop.checkout.cart.index'
    ])->name('shop.checkout.cart.remove');

    Route::post('move/wishlist/{id}', [CartController::class, 'moveToWishlist'])->name('shop.movetowishlist');

    /**
     * Coupon routes.
     */
    Route::post('checkout/cart/coupon', [CartController::class, 'applyCoupon'])->name('shop.checkout.cart.coupon.apply');

    Route::delete('checkout/cart/coupon', [CartController::class, 'removeCoupon'])->name('shop.checkout.coupon.remove.coupon');

    /**
     * Checkout routes.
     */
    Route::get('/checkout/onepage', [OnepageController::class, 'index'])->defaults('_config', [
        'view' => 'shop::checkout.onepage'
    ])->name('shop.checkout.onepage.index');

    Route::get('/checkout/summary', [OnepageController::class, 'summary'])->name('shop.checkout.summary');

    Route::post('/checkout/save-address', [OnepageController::class, 'saveAddress'])->name('shop.checkout.save-address');

    Route::post('/checkout/save-shipping', [OnepageController::class, 'saveShipping'])->name('shop.checkout.save-shipping');

    Route::post('/checkout/save-payment', [OnepageController::class, 'savePayment'])->name('shop.checkout.save-payment');

    Route::post('/checkout/check-minimum-order', [OnepageController::class, 'checkMinimumOrder'])->name('shop.checkout.check-minimum-order');

    Route::post('/checkout/save-order', [OnepageController::class, 'saveOrder'])->name('shop.checkout.save-order');

    Route::get('/checkout/success', [OnepageController::class, 'success'])->defaults('_config', [
        'view' => 'shop::checkout.success'
    ])->name('shop.checkout.success');

    Route::prefix('customer')->group(function () {
        /**
         * For customer exist check.
         */
        Route::post('/customer/exist', [OnepageController::class, 'checkExistCustomer'])->name('customer.checkout.exist');

        /**
         * For customer login checkout.
         */
        Route::post('/customer/checkout/login', [OnepageController::class, 'loginForCheckout'])->name('customer.checkout.login');
    });
});
