<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::get('/booking-slots/{id}', 'Webkul\BookingProduct\Http\Controllers\Shop\BookingProductController@index')->name('booking_product.slots.index');
});