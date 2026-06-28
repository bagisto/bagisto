<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Admin\CommissionController;
use Webkul\Marketplace\Http\Controllers\Admin\PayoutController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerController;
use Webkul\Marketplace\Http\Controllers\Admin\SubscriptionPlanController;

Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
Route::get('sellers/{id}', [SellerController::class, 'view'])->name('sellers.view');
Route::post('sellers/{id}/approve', [SellerController::class, 'approve'])->name('sellers.approve');
Route::post('sellers/{id}/suspend', [SellerController::class, 'suspend'])->name('sellers.suspend');
Route::post('sellers/{id}/payout-mode', [SellerController::class, 'updatePayoutMode'])->name('sellers.payout-mode');

Route::get('commissions', [CommissionController::class, 'index'])->name('commissions.index');
Route::post('commissions/{id}/pay', [CommissionController::class, 'markPaid'])->name('commissions.pay');

Route::get('payouts', [PayoutController::class, 'index'])->name('payouts.index');
Route::post('payouts/{id}/approve', [PayoutController::class, 'approve'])->name('payouts.approve');
Route::post('payouts/{id}/reject', [PayoutController::class, 'reject'])->name('payouts.reject');

Route::get('subscriptions', [SubscriptionPlanController::class, 'index'])->name('subscriptions.index');
Route::get('subscriptions/create', [SubscriptionPlanController::class, 'create'])->name('subscriptions.create');
Route::post('subscriptions', [SubscriptionPlanController::class, 'store'])->name('subscriptions.store');
Route::get('subscriptions/{id}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscriptions.edit');
Route::put('subscriptions/{id}', [SubscriptionPlanController::class, 'update'])->name('subscriptions.update');
Route::delete('subscriptions/{id}', [SubscriptionPlanController::class, 'destroy'])->name('subscriptions.destroy');
