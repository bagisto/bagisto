<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CartController;
use Webkul\Shop\Http\Controllers\API\CategoryController;
use Webkul\Shop\Http\Controllers\API\CompareController;
use Webkul\Shop\Http\Controllers\API\ProductController;
use Webkul\Shop\Http\Controllers\API\ReviewController;
use Webkul\Shop\Http\Controllers\Customer\WishlistController;

Route::group(['middleware' => ['locale', 'theme', 'currency'], 'prefix' => 'api'], function () {
    Route::controller(ProductController::class)->group(function () {
        Route::get('products', 'index')
            ->name('shop.products.index');

        Route::get('products/{id}/related', 'relatedProducts')
            ->name('shop.products.related.index');

        Route::get('products/{id}/up-sell', 'upSellProducts')
            ->name('shop.products.up-sell.index');
    });

    Route::controller(ReviewController::class)->group(function () {
        Route::get('product/{id}/reviews', 'index')
            ->name('shop.products.reviews.index');

        Route::post('product/{id}/review', 'store')
            ->name('shop.products.reviews.store');
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

    Route::controller(CompareController::class)->prefix('compare-items')->group(function () {
        Route::get('', 'index')->name('shop.customers.compare.index');

        Route::post('', 'store')->name('shop.customers.compare.store');

        Route::delete('', 'destroy')->name('shop.customers.compare.destroy');

        Route::post('move', 'moveToCart')->name('shop.checkout.cart.move');

        Route::post('moveToWishlist', 'moveToWishlist')->name('shop.checkout.cart.move-to-wishlist');
    });

    Route::group(['middleware' => ['customer']], function () {
        Route::post('wishlist-items/{product_id}', [WishlistController::class, 'store'])
            ->name('shop.customers.account.wishlist.store');

    });
});
