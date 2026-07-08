<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;
use Webkul\PayU\Http\Controllers\PayUController;

Route::group(['middleware' => ['web']], function () {
    Route::controller(PayUController::class)
        ->prefix('payu')
        ->group(function () {
            Route::get('redirect', 'redirect')->name('payu.redirect');

            Route::post('success', 'success')
                ->withoutMiddleware(PreventRequestForgery::class)
                ->name('payu.success');

            Route::post('failure', 'failure')
                ->withoutMiddleware(PreventRequestForgery::class)
                ->name('payu.failure');

            Route::post('cancel', 'cancel')
                ->withoutMiddleware(PreventRequestForgery::class)
                ->name('payu.cancel');
        });
});
