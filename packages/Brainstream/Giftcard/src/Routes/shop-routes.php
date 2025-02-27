<?php

use Illuminate\Support\Facades\Route;
use Brainstream\Giftcard\Http\Controllers\Shop\GiftcardController;
use Brainstream\Giftcard\Http\Controllers\Customer\Account\OrderController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'giftcard'], function () {
    Route::get('', [GiftcardController::class, 'index'])->name('shop.giftcard.index');

    Route::get('success', [GiftcardController::class, 'success'])->name('shop.giftcard.success');

    Route::post('purchase', [GiftcardController::class, 'purchase'])->name('shop.api.gift-cards.purchase');

    Route::post('store-payment-method', [GiftcardController::class, 'storePaymentMethod'])->name('shop.giftcard.store-payment-method');

    Route::post('placeorder', [GiftcardController::class, 'placeOrder'])->name('shop.api.gift-cards.placeorder');

    Route::post('checkstatus', [GiftcardController::class, 'checkstatusGiftcard'])->name('shop.api.checkout.cart.giftcard.checkstatus');
    Route::post('activate', [GiftcardController::class, 'activateGiftcard'])->name('shop.api.checkout.cart.giftcard.activate');
    Route::delete('remove', [GiftcardController::class, 'destroyGiftcard'])->name('shop.api.checkout.cart.giftcard.remove');

});

Route::group(['middleware' => ['web', 'customer']], function () {
    Route::post('customer/orders/cancel/{id}', [OrderController::class, 'cancel'])->name('shop.customers.account.order.cancel');
});
