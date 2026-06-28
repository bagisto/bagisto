<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Seller\AuthController;
use Webkul\Marketplace\Http\Controllers\Seller\DashboardController;
use Webkul\Marketplace\Http\Controllers\Seller\OrderController;
use Webkul\Marketplace\Http\Controllers\Seller\PayoutController;
use Webkul\Marketplace\Http\Controllers\Seller\ProductController;
use Webkul\Marketplace\Http\Controllers\Seller\StripeConnectController;
use Webkul\Marketplace\Http\Controllers\Seller\SubscriptionController;

Route::get('register', [AuthController::class, 'create'])->name('register');
Route::post('register', [AuthController::class, 'store'])->name('register.store');

Route::middleware(['customer'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [OrderController::class, 'view'])->name('orders.view');

    Route::get('payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::post('payouts/request', [PayoutController::class, 'request'])->name('payouts.request');

    // Stripe Connect onboarding (receive automatic payouts)
    Route::get('connect', [StripeConnectController::class, 'index'])->name('connect.index');
    Route::post('connect/onboard', [StripeConnectController::class, 'onboard'])->name('connect.onboard');
    Route::get('connect/return', [StripeConnectController::class, 'return'])->name('connect.return');

    // Seller subscription plans (recurring fee to the platform)
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions/{planId}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('subscriptions/{plan}/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
    Route::post('subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});
