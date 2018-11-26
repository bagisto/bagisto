<?php

Route::group(['namespace' => 'Webkul\API\Http\Controllers\Customer', 'prefix' => 'api/customer'], function ($router) {
    //customer registration API
    Route::post('/register', 'RegistrationController@create');

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

    //cart -> not to be used
    //active + inactive instances of cart for the current logged in user
    Route::get('get/all', 'CustomerController@getAllCart');
    //active instances of cart for the current logged in user
    Route::get('get/active', 'CustomerController@getActiveCart');
});

//all the cart related API for customer and guests
Route::group(['namespace' => 'Webkul\API\Http\Controllers\Shop', 'prefix' => 'api/cart'], function ($router) {
    //cart
    Route::get('get', 'CartController@get');
    //add item in the cart
    Route::post('add/{id}', 'CartController@add');
    //remove item to the cart
    Route::get('remove/{id}', 'CartController@remove');
    //get cart and all item for cart checkout
    Route::get('/onepage', 'CartController@onePage');
    //update OnePage
    Route::put('/update/onepage', 'CartController@updateOnePage');
});

//all the product related API to be used for store front
Route::group(['namespace' => 'Webkul\API\Http\Controllers\Product', 'prefix' => 'api/product'], function ($router) {
    //product
    //to get all products
    Route::get('get/all', 'ProductController@getAll');
    //to fetch the new products
    Route::get('get/new', 'ProductController@getNew');
    //to fetch the featured product
    Route::get('get/featured', 'ProductController@getFeatured');
    //to get the product by its slug
    Route::get('get/{slug}', 'ProductController@getBySlug');

    //product reviews get and create
    //to get the reviews of the product
    Route::get('review/{slug}', 'ReviewController@show');
    //to store the review for a product
    Route::post('review/{slug}', 'ReviewController@create');
});

//all the shop related API to be used for store front
Route::group(['namespace' => 'Webkul\API\Http\Controllers\Shop', 'prefix' => 'api/shop'], function ($router) {
    Route::get('get/category', 'CategoryController@get');
});