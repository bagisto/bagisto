<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Support\Facades\Route;
use Webkul\Cashfree\Http\Controllers\CashfreeController;

Route::group(['middleware' => ['web'], 'prefix' => 'cashfree'], function () {
    Route::get('/redirect', [CashfreeController::class, 'redirect'])
        ->name('cashfree.redirect');

    Route::match(['get', 'post'], '/callback', [CashfreeController::class, 'callback'])
        ->withoutMiddleware(PreventRequestForgery::class)
        ->name('cashfree.callback');

    Route::get('/cancel', [CashfreeController::class, 'cancel'])
        ->name('cashfree.cancel');

    Route::post('/webhook', [CashfreeController::class, 'webhook'])
        ->withoutMiddleware(PreventRequestForgery::class)
        ->name('cashfree.webhook');
});
