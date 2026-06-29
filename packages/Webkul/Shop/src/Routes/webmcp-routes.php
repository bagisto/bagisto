<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\WebMcpController;

Route::group(['prefix' => 'webmcp'], function () {
    Route::get('product', [WebMcpController::class, 'product'])->name('shop.webmcp.product');

    Route::get('category', [WebMcpController::class, 'category'])->name('shop.webmcp.category');

    Route::get('wishlist/add', [WebMcpController::class, 'addToWishlist'])->name('shop.webmcp.wishlist.add');
});
