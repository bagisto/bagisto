<?php

use Illuminate\Support\Facades\Route;
use Webkul\PayU\Http\Controllers\PayUController;

Route::middleware(['locale', 'theme', 'currency'])->prefix('payu')->group(function () {

    Route::controller(PayUController::class)->group(function () {
        /**
         * Redirect to PayU payment gateway
         */
        Route::get('redirect', 'redirect')->name('payu.redirect');

        /**
         * Payment success callback
         */
        Route::post('success', 'success')->name('payu.success');

        /**
         * Payment failure callback
         */
        Route::post('failure', 'failure')->name('payu.failure');

        /**
         * Payment cancel callback
         */
        Route::post('cancel', 'cancel')->name('payu.cancel');
    });
});