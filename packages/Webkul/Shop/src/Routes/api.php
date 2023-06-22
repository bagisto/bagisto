<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CartController;
use Webkul\Shop\Http\Controllers\API\CategoryController;
use Webkul\Shop\Http\Controllers\API\CompareController;
use Webkul\Shop\Http\Controllers\API\ProductController;
use Webkul\Shop\Http\Controllers\API\ReviewController;
use Webkul\Shop\Http\Controllers\API\WishlistController;
use Webkul\Shop\Http\Controllers\API\AddressController;

Route::group(['middleware' => ['locale', 'theme', 'currency'], 'prefix' => 'api'], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index')
            ->name('shop.api.products.index');

        Route::get('products/{id}/related', 'relatedProducts')
            ->name('shop.api.products.related.index');

        Route::get('products/{id}/up-sell', 'upSellProducts')
            ->name('shop.api.products.up-sell.index');
    });

    Route::controller(ReviewController::class)->group(function () {
        Route::get('product/{id}/reviews', 'index')
            ->name('shop.api.products.reviews.index');

        Route::post('product/{id}/review', 'store')
            ->name('shop.api.products.reviews.store');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('categories/{id}/attributes', 'getAttributes')
            ->name('shop.api.categories.attributes');

        Route::get('categories/{id}/max-price', 'getProductMaxPrice')
            ->name('shop.api.categories.max_price');
    });

    Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
        Route::get('', 'index')->name('shop.api.checkout.cart.index');

        Route::post('', 'store')->name('shop.api.checkout.cart.store');

        Route::put('', 'update')->name('shop.api.checkout.cart.update');

        Route::delete('', 'destroy')->name('shop.api.checkout.cart.destroy');
    });

    Route::controller(CompareController::class)->prefix('compare-items')->group(function () {
        Route::get('', 'index')->name('shop.api.compare.index');

        Route::post('', 'store')->name('shop.api.compare.store');

        Route::delete('', 'destroy')->name('shop.api.compare.destroy');

        Route::post('move-to-cart', 'moveToCart')->name('shop.api.compare.move_to_cart');

        Route::post('move-to-wishlist', 'moveToWishlist')->name('shop.api.compare.move_to_wishlist');
    });

    Route::group(['middleware' => ['customer']], function () {
        Route::controller(WishlistController::class)->prefix('wishlist')->group(function () {
            Route::get('', 'index')->name('shop.api.customers.account.wishlist.index');

            Route::post('', 'store')->name('shop.api.customers.account.wishlist.store');

            Route::post('{id}/move-to-cart', 'moveToCart')->name('shop.api.customers.account.wishlist.move_to_cart');

            Route::delete('{id}', 'destroy')->name('shop.api.customers.account.wishlist.destroy');
        });

        Route::get('customer/addresses', [AddressController::class,'index'])->name('api.shop.customers.account.addresses.index');
    });
});
