<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\OnepageController;

/**
 * Cart routes.
 */
Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
    Route::get('', 'index')->name('shop.checkout.cart.index');
});

// Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
//     Route::get('', 'index')->name('shop.checkout.onepage.index');
//     Route::get('success', 'success')->name('shop.checkout.success');
// });


// Checkout route for product
Route::controller(OnepageController::class)->prefix('checkout/onepage')->group(function () {
    Route::get('', 'checkoutProduct')->name('shop.checkout.product');
    Route::post('', 'checkoutProductFinal')->name('shop.checkout.product');
    Route::get('success', 'productcheckoutSuccess')->name('shop.checkout.success');
});




Route::post('checkout/service', [OnepageController::class,'checkoutService'])->name('shop.checkout.service');

Route::get('/payment/{order}', [OnepageController::class, 'paymentPage'])
    ->name('shop.payment.page');
