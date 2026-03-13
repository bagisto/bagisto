<?php

use Illuminate\Support\Facades\Route;
use Webkul\Paytm\Http\Controllers\PaytmController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::prefix('paytm')->controller(PaytmController::class)->group(function () {
        Route::get('redirect', 'redirect')->name('paytm.redirect');

        Route::get('cancel', 'cancel')->name('paytm.cancel');

        Route::get('success', 'success')->name('paytm.success');
    });
});

Route::post('paytm/callback', [PaytmController::class, 'callback'])
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
    ->name('paytm.callback');
