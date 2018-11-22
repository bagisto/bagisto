<?php

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Customer', 'prefix' => 'api/customer'], function ($router) {
    //auth route for customer
    Route::post('login', 'AuthController@create');

    //auth route for customer
    Route::post('logout', 'AuthController@destroy');

    //get user
    Route::get('get/user', 'CustomerController@getProfile');

    //wishlist
    Route::get('get/wishlist', 'WishlistController@getWishlist');

    //address
    Route::get('get/address', 'AddressController@getAddress');
    Route::get('get/default/address', 'AddressController@getDefaultAddress');

    Route::post('create/address', 'AddressController@createAddress');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Shop', 'prefix' => 'api/cart'], function ($router) {

    //cart
    //active + inactive instances of cart for the current logged in user
    Route::get('get/all', 'CartController@getAllCart');

    //active instances of cart for the current logged in user
    Route::get('get/active', 'CartController@getActiveCart');

    //inactive instances of cart for the current logged in user
    Route::get('get/inactive', 'CartController@getInactiveCart');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Product', 'prefix' => 'api/product'], function ($router) {
    //product
    //to fetch the new product
    Route::get('get/all', 'ProductController@getAllProducts');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Admin', 'prefix' => 'api/admin'], function ($router) {

});