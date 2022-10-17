<?php

// Controllers
use Webkul\BookingProduct\Http\Controllers\Shop\BookingProductController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::get('booking-slots/{id}', [BookingProductController::class, 'index'])->name('booking_product.slots.index');
});