<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Webkul\PayU\Http\Controllers\PayUController;

Route::group(['middleware' => ['web']], function () {
    Route::controller(PayUController::class)
        ->prefix('payu')
        ->group(function () {
            Route::get('redirect', 'redirect')->name('payu.redirect');

            Route::post('success', 'success')
                ->withoutMiddleware(VerifyCsrfToken::class)
                ->name('payu.success');

            Route::post('failure', 'failure')
                ->withoutMiddleware(VerifyCsrfToken::class)
                ->name('payu.failure');

            Route::post('cancel', 'cancel')
                ->withoutMiddleware(VerifyCsrfToken::class)
                ->name('payu.cancel');
        });
});
