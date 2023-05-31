<?php

use Illuminate\Support\Facades\Route;
use Webkul\BookingProduct\Http\Controllers\Admin\BookingController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('sales')->group(function () {
        Route::get('bookings', [BookingController::class, 'index'])->name('admin.sales.bookings.index');

        Route::get('bookings/get', [BookingController::class, 'get'])->name('admin.sales.bookings.get');
    });
});
