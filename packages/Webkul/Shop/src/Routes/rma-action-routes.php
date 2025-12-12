<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\Customer\RMAActionController;

/**
 * RMA Routes
 */
Route::prefix('rma')->group(function () {
    /**
     * RMA Action routes for guest and customer.
     *
     * These routes are used to perform actions on RMA requests such as canceling, updating status, and sending messages.
     */
    Route::controller(RMAActionController::class)->group(function () {
        Route::get('get-order-product/{orderId}', 'getOrderProduct')->name('shop.rma.action.ordered.product');

        Route::get('resolution-reason/{resolutionType}', 'getResolutionReason')->name('shop.rma.action.resolution.reasons');

        Route::post('update-status', 'saveStatus')->name('shop.rma.action.close');

        Route::post('reopen-status', 'reOpen')->name('shop.rma.action.re-open');

        Route::get('cancel/{id}', 'cancel')->name('shop.rma.action.cancel');

        Route::get('get-messages', 'getMessages')->name('shop.rma.action.get.messages');

        Route::post('send-message', 'sendMessage')->name('shop.rma.action.send.message');
    });
});
