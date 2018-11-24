<?php

Route::group(['middleware' => 'api','namespace' => 'Webkul\API\Http\Controllers\Customer', 'prefix' => 'api/customer'], function ($router) {
    Route::post('/register', 'RegistrationController@create');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Customer', 'prefix' => 'api/customer'], function ($router) {
    //auth route for customer
    Route::post('login', 'AuthController@create');

    //auth route for customer
    Route::post('logout', 'AuthController@destroy');

    //get customer profile
    Route::get('get/profile', 'CustomerController@getProfile');
    Route::put('update/profile/{id}', 'CustomerController@updateProfile');

    //wishlist
    //get wishlist
    Route::get('get/wishlist', 'WishlistController@getWishlist');
    //add the item in the wishlist
    Route::get('add/wishlist/{id}', 'WishlistController@add');
    //delete the item from the wishlist
    Route::get('delete/wishlist/{id}', 'WishlistController@delete');
    //Move the item from the wishlist to cart
    // Route::get('delete/wishlist/{id}', 'WishlistController@delete');

    //address
    Route::get('get/address', 'AddressController@get');
    Route::get('get/default/address', 'AddressController@getDefault');
    Route::post('create/address', 'AddressController@create');
    Route::put('make/default/address/{id}', 'AddressController@makeDefault');
    Route::delete('delete/address/{id}', 'AddressController@delete');

    //cart
    //active + inactive instances of cart for the current logged in user
    Route::get('get/all', 'CustomerController@getAllCart');
    //active instances of cart for the current logged in user
    Route::get('get/active', 'CustomerController@getActiveCart');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Shop', 'prefix' => 'api/cart'], function ($router) {
    //cart
    Route::get('get', 'CartController@get');
    //add item in the cart
    Route::post('add/{id}', 'CartController@add');
    //remove item to the cart
    Route::get('remove/{id}', 'CartController@remove');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Product', 'prefix' => 'api/product'], function ($router) {
    //product
    //to fetch the new product
    Route::get('get/all', 'ProductController@getAllProducts');
});

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Admin', 'prefix' => 'api/admin'], function ($router) {

});