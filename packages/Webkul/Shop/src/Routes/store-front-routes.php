<?php

use Illuminate\Support\Facades\Route;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;
use Webkul\Shop\Http\Controllers\BookingProductController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\EUWithdrawalController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\PageController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;

/**
 * EU Withdrawal — public guest flow (Directive (EU) 2023/2673, Art. 11a).
 *
 * Declared before the catch-all fallback so its URLs match first.
 */
Route::prefix('withdraw')->middleware([NoCacheMiddleware::class])->group(function () {
    Route::controller(EUWithdrawalController::class)->group(function () {
        Route::get('/', 'lookupForm')
            ->middleware('throttle:eu-withdraw-lookup')
            ->name('shop.eu-withdrawal.guest.lookup');

        Route::post('lookup', 'lookupSubmit')
            ->middleware('throttle:eu-withdraw-lookup')
            ->name('shop.eu-withdrawal.guest.lookup.submit');

        Route::get('{orderId}/create', 'guestCreate')
            ->middleware(['signed', 'throttle:eu-withdraw-submit'])
            ->name('shop.eu-withdrawal.guest.create');

        Route::post('{orderId}/store', 'guestStore')
            ->middleware(['signed', 'throttle:eu-withdraw-submit'])
            ->name('shop.eu-withdrawal.guest.store');

        Route::get('confirmation/{uuid}', 'guestConfirmation')
            ->middleware('signed')
            ->name('shop.eu-withdrawal.guest.confirmation');
    });
});

/**
 * CMS pages.
 */
Route::get('page/{slug}', [PageController::class, 'view'])
    ->name('shop.cms.page')
    ->middleware('cache.response');

/**
 * Fallback route.
 */
Route::fallback(ProductsCategoriesProxyController::class.'@index')
    ->name('shop.product_or_category.index')
    ->middleware('cache.response');

/**
 * Store front home.
 */
Route::get('/', [HomeController::class, 'index'])
    ->name('shop.home.index')
    ->middleware('cache.response');

Route::get('contact-us', [HomeController::class, 'contactUs'])
    ->name('shop.home.contact_us')
    ->middleware('cache.response');

Route::post('contact-us/send-mail', [HomeController::class, 'sendContactUsMail'])
    ->name('shop.home.contact_us.send_mail')
    ->middleware('cache.response');

/**
 * Store front search.
 */
Route::get('search', [SearchController::class, 'index'])
    ->name('shop.search.index')
    ->middleware('cache.response');

Route::post('search/upload', [SearchController::class, 'upload'])->name('shop.search.upload');

/**
 * Subscription routes.
 */
Route::controller(SubscriptionController::class)->group(function () {
    Route::post('subscription', 'store')->name('shop.subscription.store');

    Route::get('subscription/{token}', 'destroy')->name('shop.subscription.destroy');
});

/**
 * Compare products
 */
Route::get('compare', [CompareController::class, 'index'])
    ->name('shop.compare.index')
    ->middleware('cache.response');

/**
 * Downloadable products
 */
Route::controller(ProductController::class)->group(function () {
    Route::get('downloadable/download-sample/{type}/{id}', 'downloadSample')->name('shop.downloadable.download_sample');

    Route::get('product/{id}/{attribute_id}', 'download')->name('shop.product.file.download');
});

/**
 * Booking products
 */
Route::get('booking-slots/{id}', [BookingProductController::class, 'index'])
    ->name('shop.booking-product.slots.index');
