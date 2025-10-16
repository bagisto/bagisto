<?php

use Illuminate\Support\Facades\Route;
use Webkul\Razorpay\Http\Controllers\RazorpayController;

Route::controller(RazorpayController::class)->middleware('web')->prefix('razorpay/payment')->group(function () {
    Route::get('redirect', 'redirect')->name('razorpay.payment.redirect');
    
    Route::get('success', 'paymentSuccess')->name('razorpay.payment.success');

    Route::get('fail', 'paymentFail')->name('razorpay.payment.cancel');
});