<?php

use Illuminate\Support\Facades\Route;
use Webkul\Marketplace\Http\Controllers\Shop\StoreController;

/*
 * Public seller storefront — a unique, shareable link per seller:
 *   https://your-site.com/loja/{shop_url}
 */
Route::get('loja/{shopUrl}', [StoreController::class, 'show'])->name('store');
