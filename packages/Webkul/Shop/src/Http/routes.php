<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::home.index'
    ]);

    Route::get('/categories/{slug}', 'Webkul\Shop\Http\Controllers\ProductController@index')->defaults('_config', [
        'view' => 'shop::products.index'
    ]);
    
    Route::view('/customer/order','shop::customers.account.orders.index');

    Route::view('/customer/checkout','shop::customers.checkout.index');

    Route::view('/customer/signin','shop::customers.checkout.signin');

    Route::view('/customer/ship_method','shop::customers.checkout.ship-method');

    Route::view('/customer/payment_method','shop::customers.checkout.payment-method');

    Route::view('/customer/payment_complete','shop::customers.checkout.complete');

});