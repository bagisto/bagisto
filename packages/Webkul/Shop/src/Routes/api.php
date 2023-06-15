<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\API\CartController;
use Webkul\Shop\Http\Controllers\API\CategoryController;
use Webkul\Shop\Http\Controllers\API\ProductController;
use Webkul\Shop\Http\Controllers\API\ReviewController;
use Webkul\Shop\Http\Controllers\API\WishlistController;
use Webkul\Shop\Http\Controllers\API\AddressController;
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

    Route::controller(CartController::class)->prefix('checkout/cart')->group(function () {
        Route::get('', 'index')->name('shop.checkout.cart.index');

        Route::post('', 'store')->name('shop.checkout.cart.store');

        Route::put('', 'update')->name('shop.checkout.cart.update');

        Route::delete('', 'destroy')->name('shop.checkout.cart.destroy');
    });

    Route::group(['middleware' => ['customer']], function () {
        Route::controller(WishlistController::class)->prefix('wishlist')->group(function () {
            /**
             * To Do (@shivendra):
             *
             * Need to fix the `api` for all route.
             */
            Route::get('', 'index')->name('api.shop.customers.account.wishlist.index');

            Route::post('', 'store')->name('shop.customers.account.wishlist.store');

            Route::post('{id}/move-to-cart', 'moveToCart')->name('shop.customers.account.wishlist.move_to_cart');

            Route::delete('{id}', 'destroy')->name('shop.customers.account.wishlist.destroy');
        });

        Route::get('compare-items/{product_id}', [CompareController::class, 'store'])
            ->name('shop.customers.account.compare.store');

        Route::get('/customer/addresses', [AddressController::class,'index'])->name('api.shop.customers.account.addresses.index');
    });
});
