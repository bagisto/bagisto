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
    Route::get('/', [HomeController::class, 'index'])->name('shop.home.index');

    /**
     * Store front search.
     */
    Route::get('search', [SearchController::class, 'index'])->name('shop.search.index');

    Route::post('upload-search-image', [HomeController::class, 'upload'])->name('shop.image.search.upload');

    /**
     * Countries and states.
     */
    Route::controller(CountryStateController::class)->prefix('countries')->group(function () {
        Route::get('', 'getCountries')->name('shop.countries');

        Route::get('states', 'getStates')->name('shop.countries.states');
    });

    /**
     * Subscription routes.
     */
    Route::controller(SubscriptionController::class)->group(function () {
        Route::post('subscribe', 'subscribe')->name('shop.subscribe');

        Route::get('unsubscribe/{token}', 'unsubscribe')->name('shop.unsubscribe');
    });

    /**
     * Compare products
     */
    Route::get('compare', [CompareController::class, 'index'])->name('shop.compare.index');

    /**
     * Downloable products
     */
    Route::controller(ProductController::class)->group(function () {
        Route::get('downloadable/download-sample/{type}/{id}', 'downloadSample')->name('shop.downloadable.download_sample');

        Route::get('product/{id}/{attribute_id}', 'download')->defaults('_config', [
            'view' => 'shop.products.index',
        ])->name('shop.product.file.download');
    });

});
