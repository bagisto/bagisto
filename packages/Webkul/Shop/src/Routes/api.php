<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CategoryController;
use Webkul\Shop\Http\Controllers\API\ProductController;
use Webkul\Shop\Http\Controllers\API\ReviewController;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\Customer\WishlistController;
use Webkul\Shop\Http\Controllers\Customer\Account\CompareController;

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

    Route::post('cart', [CartController::class, 'store'])
        ->name('shop.customers.cart.store');

    Route::group(['middleware' => ['customer']], function () {
        Route::post('wishlist-items/{product_id}', [WishlistController::class, 'store'])
            ->name('shop.customers.account.wishlist.store');

        Route::get('compare-items/{product_id}', [CompareController::class, 'store'])
            ->name('shop.customers.account.compare.store');
    });
});
