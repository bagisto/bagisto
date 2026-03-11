<?php

use Illuminate\Support\Facades\Route;
use Webkul\Paytm\Http\Controllers\PaytmController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::prefix('paytm')->group(function () {
        Route::get('/redirect', [PaytmController::class, 'redirect'])->name('paytm.redirect');

        Route::get('/cancel', [PaytmController::class, 'cancel'])->name('paytm.cancel');

        Route::get('/success', [PaytmController::class, 'success'])->name('paytm.success');
    });
});

Route::post('paytm/callback', [PaytmController::class, 'callback'])
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
    ->name('paytm.callback');
