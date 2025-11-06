<?php

use Illuminate\Support\Facades\Route;
use Webkul\Stripe\Http\Controllers\StripeConnectController;
use Webkul\Stripe\Http\Controllers\StripeWebhookController;

Route::middleware('locale', 'theme', 'currency')->prefix('checkout')->group(function () {

    Route::controller(StripeConnectController::class)->group(function () {
        Route::get('stripe/card/delete', 'deleteCard')->name('stripe.delete.saved.cart');

        Route::get('sendtoken', 'collectToken')->name('stripe.get.token');

        Route::get('create/charge', 'createCharge')->name('stripe.make.payment');

        Route::post('save/card', 'saveCard')->name('stripe.save.card');

        Route::post('saved/card/payment', 'savedCardPayment')->name('stripe.saved.card.payment');

        Route::get('redirect/stripe', 'redirect')->name('stripe.standard.redirect');

        Route::get('payment/cancel', 'paymentCancel')->name('stripe.payment.cancel');

        Route::get('payment/element/intent', 'elementpaymentIntent')->name('stripe.payment.element.intent');
    });
});

Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
