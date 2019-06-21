<?php

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['admin']], function () {
        Route::get('stripe/connect', 'Webkul\StripeConnect\Http\Controllers\SellerRegistrationController@index')->name('admin.stripe.seller');

        Route::get('stripe/connect/retrieve/token', 'Webkul\StripeConnect\Http\Controllers\SellerRegistrationController@retrieveToken')->name('admin.stripe.retrieve-grant');

        Route::get('stripe/connect/revoke', 'Webkul\StripeConnect\Http\Controllers\SellerRegistrationController@revokeAccess')->name('admin.stripe.revoke-access');
    });
});

Route::prefix('super')->group(function () {
    Route::get('stripe/create', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@createDetails')->name('admin.stripe.create-details');

    Route::post('stripe/store', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@storeDetails')->name('admin.stripe.store-details');

    Route::get('stripe/edit', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@editDetails')->name('admin.stripe.edit-details');

    Route::post('stripe/update', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@updateDetails')->name('admin.stripe.update-details');
});

Route::group(['middleware' => ['web']], function () {
// Route::middleware('web')->group(function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/stripe', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@collectCard')->defaults('_config', [
            'view' => 'stripe::checkout.card'
        ])->name('stripe.cardcollect');

        Route::get('/stripe/card/check', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@checkCard')->name('stripe.check.card.unique');

        Route::get('/stripe/card/delete', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@deleteCard')->name('stripe.delete.saved.cart');

        Route::post('/sendtoken', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@collectToken')->name('stripe.get.token');

        Route::get('/create/charge', 'Webkul\StripeConnect\Http\Controllers\StripeConnectController@createCharge')->name('stripe.make.payment');
    });
});