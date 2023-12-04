<?php

use Illuminate\Support\Facades\Route;
use Webkul\BookingProduct\Http\Controllers\Admin\BookingController;

/**
 * Sales routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {

    Route::controller(BookingController::class)->prefix('sales/bookings')->group(function () {
        Route::get('', 'index')->name('admin.sales.bookings.index');

        Route::get('get', 'get')->name('admin.sales.bookings.get');
    });

});
