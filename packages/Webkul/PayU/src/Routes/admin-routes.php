<?php

use Illuminate\Support\Facades\Route;
use Webkul\PayU\Http\Controllers\RefundController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/sales/payu'], function () {

    Route::controller(RefundController::class)->group(function () {
        /**
         * Create refund for PayU payment
         */
        Route::post('refunds/create/{order_id}', 'store')->name('admin.sales.payu.refunds.store');
    });
});