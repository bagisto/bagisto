<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Admin\SellerController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerProductController;
use Webkul\Marketplace\Http\Controllers\Admin\SellerOrderController;
use Webkul\Marketplace\Http\Controllers\Admin\TransactionController;
use Webkul\Marketplace\Http\Controllers\Admin\ReviewController;

Route::group([
    'middleware' => ['admin'],
    'prefix'     => config('app.admin_url') . '/marketplace',
], function () {
    Route::controller(SellerController::class)->prefix('sellers')->group(function () {
        Route::get('', 'index')->name('admin.marketplace.sellers.index');
        Route::get('{id}/edit', 'edit')->name('admin.marketplace.sellers.edit');
        Route::put('{id}', 'update')->name('admin.marketplace.sellers.update');
        Route::delete('{id}', 'destroy')->name('admin.marketplace.sellers.delete');
        Route::post('{id}/approve', 'approve')->name('admin.marketplace.sellers.approve');
    });

    Route::controller(SellerProductController::class)->prefix('seller-products')->group(function () {
        Route::get('', 'index')->name('admin.marketplace.seller-products.index');
        Route::post('{id}/approve', 'approve')->name('admin.marketplace.seller-products.approve');
        Route::delete('{id}', 'destroy')->name('admin.marketplace.seller-products.delete');
    });

    Route::controller(SellerOrderController::class)->prefix('seller-orders')->group(function () {
        Route::get('', 'index')->name('admin.marketplace.seller-orders.index');
        Route::get('{id}', 'view')->name('admin.marketplace.seller-orders.view');
    });

    Route::controller(TransactionController::class)->prefix('transactions')->group(function () {
        Route::get('', 'index')->name('admin.marketplace.transactions.index');
        Route::post('', 'store')->name('admin.marketplace.transactions.store');
    });

    Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
        Route::get('', 'index')->name('admin.marketplace.reviews.index');
        Route::put('{id}', 'update')->name('admin.marketplace.reviews.update');
        Route::delete('{id}', 'destroy')->name('admin.marketplace.reviews.delete');
    });
});
