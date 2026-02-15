<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Shop\SellerController;
use Webkul\Marketplace\Http\Controllers\Shop\Seller\AccountController;
use Webkul\Marketplace\Http\Controllers\Shop\Seller\DashboardController;
use Webkul\Marketplace\Http\Controllers\Shop\Seller\ProductController;
use Webkul\Marketplace\Http\Controllers\Shop\Seller\OrderController;
use Webkul\Marketplace\Http\Controllers\Shop\Seller\ReviewController;

/**
 * Public seller routes.
 */
Route::group([
    'prefix' => 'marketplace',
], function () {
    Route::get('sellers', [SellerController::class, 'index'])->name('marketplace.sellers.index');
    Route::get('seller/{url}', [SellerController::class, 'show'])->name('marketplace.seller.show');
    Route::post('seller/{url}/review', [SellerController::class, 'storeReview'])
        ->middleware('customer')
        ->name('marketplace.seller.review.store');
});

/**
 * Seller account routes (authenticated customers).
 */
Route::group([
    'middleware' => ['customer'],
    'prefix'     => 'marketplace/seller',
], function () {
    /**
     * Registration & Account.
     */
    Route::get('register', [AccountController::class, 'showRegistrationForm'])->name('marketplace.seller.register');
    Route::post('register', [AccountController::class, 'register'])->name('marketplace.seller.register.store');
    Route::get('account', [AccountController::class, 'edit'])->name('marketplace.seller.account.edit');
    Route::put('account', [AccountController::class, 'update'])->name('marketplace.seller.account.update');

    /**
     * Seller Dashboard.
     */
    Route::get('dashboard', [DashboardController::class, 'index'])->name('marketplace.seller.dashboard');

    /**
     * Seller Products.
     */
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('', 'index')->name('marketplace.seller.products.index');
        Route::get('create', 'create')->name('marketplace.seller.products.create');
        Route::post('', 'store')->name('marketplace.seller.products.store');
        Route::get('{id}/edit', 'edit')->name('marketplace.seller.products.edit');
        Route::put('{id}', 'update')->name('marketplace.seller.products.update');
        Route::delete('{id}', 'destroy')->name('marketplace.seller.products.delete');
    });

    /**
     * Seller Orders.
     */
    Route::controller(OrderController::class)->prefix('orders')->group(function () {
        Route::get('', 'index')->name('marketplace.seller.orders.index');
        Route::get('{id}', 'view')->name('marketplace.seller.orders.view');
    });

    /**
     * Seller Reviews.
     */
    Route::get('reviews', [ReviewController::class, 'index'])->name('marketplace.seller.reviews.index');
});
