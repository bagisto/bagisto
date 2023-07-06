<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\CMS\PagePresenterController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\CountryStateController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {
    /**
     * CMS pages.
     */
    Route::get('page/{slug}', [PagePresenterController::class, 'presenter'])->name('shop.cms.page');

    /**
     * Fallback route.
     */
    Route::fallback(ProductsCategoriesProxyController::class . '@index')
        ->name('shop.productOrCategory.index');

    /**
     * Store front home.
     */
    Route::get('/', [HomeController::class, 'index'])->defaults('_config', [
        'view' => 'shop::home.index',
    ])->name('shop.home.index');

    /**
     * Store front search.
     */
    Route::get('search', [SearchController::class, 'index'])->name('shop.search.index');

    Route::post('upload-search-image', [HomeController::class, 'upload'])->name('shop.image.search.upload');

    /**
     * Countries and states.
     */
    Route::get('countries', [CountryStateController::class, 'getCountries'])->name('shop.countries');
    Route::get('countries/states', [CountryStateController::class, 'getStates'])->name('shop.countries.states');

    /**
     * Subscription routes.
     */
    Route::post('subscribe', [SubscriptionController::class, 'subscribe'])->name('shop.subscribe');

    Route::get('unsubscribe/{token}', [SubscriptionController::class, 'unsubscribe'])->name('shop.unsubscribe');

    /**
     * Compare products
     */
    Route::get('compare', [CompareController::class, 'index'])->name('shop.compare.index');

    Route::get('downloadable/download-sample/{type}/{id}', [ProductController::class, 'downloadSample'])->name('shop.downloadable.download_sample');

    Route::get('product/{id}/{attribute_id}', [ProductController::class, 'download'])->defaults('_config', [
        'view' => 'shop.products.index',
    ])->name('shop.product.file.download');
});
