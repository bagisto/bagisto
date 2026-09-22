<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Webkul\Stripe\Http\Controllers\StripeController;

Route::controller(StripeController::class)
    ->middleware('web')
    ->prefix('stripe')
    ->group(function () {
        Route::get('redirect', 'redirect')->name('stripe.standard.redirect');

        Route::get('success', 'success')->name('stripe.payment.success');

        Route::get('cancel', 'cancel')->name('stripe.payment.cancel');

        Route::post('webhook', 'webhook')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('stripe.webhook');
    });
