<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\Customer\Account\CompareController;
use Webkul\Shop\Http\Controllers\Customer\WishlistController;
use Webkul\Shop\Http\Controllers\CategoryController;

Route::group(['middleware' => ['locale', 'theme', 'currency'], 'prefix' => 'api'], function () {

    Route::controller(ProductController::class)->group(function () {
        Route::get('category-products', 'index')
            ->name('shop.category_products.get');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories/{id}/filter-attributes', 'getAttributes')
            ->name('shop.categories.filterable_attributes');

        Route::get('categories/{id}/maximum-price', 'getProductMaximumPrice')
            ->name('shop.categories.maximum-price');
    });


    Route::post('/cart/add', [CartController::class, 'store'])
        ->name('shop.customers.cart.store');

    Route::group(['middleware' => ['customer']], function () {
        Route::get('wishlist/create/{id}', [WishlistController::class, 'store'])
            ->name('shop.customers.account.wishlist.store');

        Route::get('customer/items/compare/{id}', [CompareController::class, 'store'])
            ->name('shop.customers.account.compare.store');

    });
});

?>