<?php

use Frosko\DSK\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('dsk/payment')->group(function () {
    Route::group(['middleware' => ['web']], function () {
        Route::get('/redirect', [PaymentController::class, 'redirect'])->name('dsk.payment.redirect');

        Route::get('/success', [PaymentController::class, 'success'])->name('dsk.payment.success');

        Route::get('/failure', [PaymentController::class, 'failure'])->name('dsk.payment.failure');
    });

    Route::post('ipn', [PaymentController::class, 'ipn'])
        ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
        ->name('dsk.payment.ipn');
});
