<?php

use Illuminate\Support\Facades\Route;
use Webkul\Stripe\Http\Controllers\RefundController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'checkout'], function () {

    Route::controller(RefundController::class)->group(function () {
        Route::post('refunds/create/{order_id}', 'store')->name('admin.sales.stripe.refunds.store');

        Route::get('refunds/create/{order_id}', 'create')->name('admin.sales.stripe.refunds.create');
    });
});
