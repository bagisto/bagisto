<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CategoryController;
use Webkul\Shop\Http\Controllers\API\CartController;
use Webkul\Shop\Http\Controllers\API\ProductController;
use Webkul\Shop\Http\Controllers\Customer\Account\CompareController;
use Webkul\Shop\Http\Controllers\Customer\WishlistController;

Route::group(['middleware' => ['locale', 'theme', 'currency'], 'prefix' => 'api'], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index')
            ->name('shop.products.index');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories/{id}/attributes', 'getAttributes')
            ->name('shop.categories.attributes');

        Route::get('categories/{id}/max-price', 'getProductMaxPrice')
            ->name('shop.categories.max_price');
    });

    Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
        Route::get('', 'index')->name('shop.checkout.cart.index');

        Route::post('', 'store')->name('shop.checkout.cart.store');

        Route::put('', 'update')->name('shop.checkout.cart.update');

        Route::delete('', 'destroy')->name('shop.checkout.cart.destroy');
    });

    Route::group(['middleware' => ['customer']], function () {
        Route::post('wishlist-items/{product_id}', [WishlistController::class, 'store'])
            ->name('shop.customers.account.wishlist.store');

        Route::get('compare-items/{product_id}', [CompareController::class, 'store'])
            ->name('shop.customers.account.compare.store');
    });
});
