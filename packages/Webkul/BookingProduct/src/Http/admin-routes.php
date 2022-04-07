<?php

/**
 * Sales routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('sales')->group(function () {
        /**
         * Booking routes.
         */
        Route::get('/bookings', [Webkul\BookingProduct\Http\Controllers\Admin\BookingController::class, 'index'])->defaults('_config', [
            'view' => 'bookingproduct::admin.sales.bookings.index',
        ])->name('admin.sales.bookings.index');

        Route::get('/bookings/get', [Webkul\BookingProduct\Http\Controllers\Admin\BookingController::class, 'get'])->name('admin.sales.bookings.get');
    });
});
