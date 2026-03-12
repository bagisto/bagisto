<?php

use Illuminate\Support\Facades\Route;
use Webkul\Shop\Http\Controllers\BookingProductController;
use Webkul\Shop\Http\Controllers\CompareController;
use Webkul\Shop\Http\Controllers\HomeController;
use Webkul\Shop\Http\Controllers\CustomerController;
use Webkul\Shop\Http\Controllers\CartController;
use Webkul\Shop\Http\Controllers\PageController;
use Webkul\Shop\Http\Controllers\ProductController;
use Webkul\Shop\Http\Controllers\ProductsCategoriesProxyController;
use Webkul\Shop\Http\Controllers\SearchController;
use Webkul\Shop\Http\Controllers\SubscriptionController;

// Main Landing Page
Route::view('/', 'shop::home.landing')->name('spa.home');

// Inner Landing Page
Route::get('/spa-services', [HomeController::class, 'index'])->name('shop.home.index');
Route::get('sbt-perfumes', [HomeController::class,'sbtPerfumeIndex'])->name('sbt.perfume.index');
Route::post('sbt-perfumes', [SearchController::class,'sbtPerfumeSearch'])->name('sbt.perfumes.search');
Route::get('spa-products', [HomeController::class,'spaProductsIndex'])->name('spa.product.index');
Route::post('spa-products', [SearchController::class,'spaProductsSearch'])->name('spa.product.search');
Route::get('flower-products', [HomeController::class,'flowerProductsIndex'])->name('flower.product.index');
Route::post('flower-products', [SearchController::class,'flowerProductsSearch'])->name('flower.product.search');

// Header Routes
// Language Switch
Route::get('/switch/lang/{ln}', [HomeController::class,'switchLanguage'])->name('switch.language');
// Search box
Route::get('/booking/search', [SearchController::class, 'serviceSearchResult'])->name('booking.search');
// customer profile
Route::get('customer/profile', [CustomerController::class, 'customerProfileIndex'])->name('customer.profile.index');

// Cart routes only for products (simple products)
Route::get('cart/index', [CartController::class,'indexCart'])->name('shop.cart.index');
Route::post('cart/add/{slug}', [CartController::class,'addToCart'])->name('shop.add.cart');
Route::delete('cart/remove/{id}', [CartController::class,'removeCartItem'])->name('shop.remove.cart');

// Services as per category
Route::get('/services/{slug}', [HomeController::class, 'servicesByCategory'])
    ->name('shop.home.services');

Route::get('/single/service/{slug}', [HomeController::class, 'singleServicesByCategory'])
    ->name('shop.home.services.single');

// Service details page
Route::get('/service/{url_key}', [HomeController::class, 'servicesDetails'])
    ->name('shop.home.service.details');

// Product details page
Route::get('/product/{url_key}', [HomeController::class, 'productDetails'])
    ->name('shop.home.product.details');

// Inner pages
// Services page
Route::get('/services', [HomeController::class, 'allServices'])
    ->name('shop.home.all.services');

// Gallery page
Route::get('/gallery', [HomeController::class, 'galleryIndex'])
    ->name('shop.gallery.index');

// About Us page
Route::get('about', [HomeController::class, 'about'])
    ->name('shop.home.aboutus');

// Contact Us page
Route::get('contact', [HomeController::class, 'contactUs'])
    ->name('shop.home.contactus');

Route::post('contact-us/send-mail', [HomeController::class, 'sendContactUsMail'])
    ->name('shop.home.contact_us.send_mail');

// Default routes
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

// Header search box
Route::get('search', [SearchController::class, 'index'])->name('shop.search.index');
