<?php

use Illuminate\Support\Facades\Route;
use Webkul\PayU\Http\Controllers\PayUController;

Route::controller(PayUController::class)
    ->middleware('web')
    ->prefix('payu')
    ->group(function () {
        Route::get('redirect', 'redirect')->name('payu.redirect');

        Route::post('success', 'success')->name('payu.success');

        Route::post('failure', 'failure')->name('payu.failure');

        Route::post('cancel', 'cancel')->name('payu.cancel');
    });
