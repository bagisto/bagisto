<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('paypal/standard')->group(function () {

        Route::get('/redirect', 'Webkul\Paypal\Http\Controllers\StandardController@redirect')->name('paypal.standard.redirect');

        Route::get('/success', 'Webkul\Paypal\Http\Controllers\StandardController@success')->name('paypal.standard.success');

        Route::get('/cancel', 'Webkul\Paypal\Http\Controllers\StandardController@cancel')->name('paypal.standard.cancel');
    });

    Route::prefix('paypal/smart-button')->group(function () {
        Route::get('/details', 'Webkul\Paypal\Http\Controllers\SmartButtonController@details')->name('paypal.smart_button.details');

        Route::post('/save-order', 'Webkul\Paypal\Http\Controllers\SmartButtonController@saveOrder')->name('paypal.smart_button.save_order');
    });
});

Route::get('paypal/standard/ipn', 'Webkul\Paypal\Http\Controllers\StandardController@ipn')->name('paypal.standard.ipn');
