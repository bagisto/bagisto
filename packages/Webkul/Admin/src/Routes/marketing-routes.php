<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\CartRule\CartRuleController;
use Webkul\Admin\Http\Controllers\CartRule\CartRuleCouponController;
use Webkul\Admin\Http\Controllers\CatalogRule\CatalogRuleController;
use Webkul\Admin\Http\Controllers\Marketing\CampaignController;
use Webkul\Admin\Http\Controllers\Marketing\EventController;
use Webkul\Admin\Http\Controllers\Marketing\SubscriptionController;
use Webkul\Admin\Http\Controllers\Marketing\TemplateController;
use Webkul\Admin\Http\Controllers\Sitemap\SitemapController;

/**
 * Marketing routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('promotions')->group(function () {
        /**
         * Cart rules routes.
         */
        Route::controller(CartRuleController::class)->prefix('cart-rules')->group(function () {
            Route::get('', 'index')->name('admin.cart_rules.index');

            Route::get('create', 'create')->name('admin.cart_rules.create');

            Route::post('create', 'store')->name('admin.cart_rules.store');

            Route::get('copy/{id}', 'copy')->name('admin.cart_rules.copy');

            Route::get('edit/{id}', 'edit')->name('admin.cart_rules.edit');

            Route::post('edit/{id}', 'update')->name('admin.cart_rules.update');

            Route::post('delete/{id}', 'destroy')->name('admin.cart_rules.delete');
        });

        /**
         * Cart rule coupons routes.
         */
        Route::controller(CartRuleCouponController::class)->prefix('cart-rules/coupons')->group(function () {
            Route::get('{id}', 'index')->name('admin.cart_rules.coupons.index');

            Route::post('{id}', 'store')->name('admin.cart_rules.coupons.store');

            Route::post('mass-delete', 'massDelete')->name('admin.cart_rules.coupons.mass_delete');
        });

        /**
         * Catalog rules routes.
         */
        Route::controller(CatalogRuleController::class)->prefix('catalog-rules')->group(function () {
            Route::get('', 'index')->name('admin.catalog_rules.index');

            Route::get('create', 'create')->name('admin.catalog_rules.create');

            Route::post('create', 'store')->name('admin.catalog_rules.store');

            Route::get('edit/{id}', 'edit')->name('admin.catalog_rules.edit');

            Route::post('edit/{id}', 'update')->name('admin.catalog_rules.update');

            Route::post('delete/{id}', 'destroy')->name('admin.catalog_rules.delete');
        });

        /**
         * Campaigns routes.
         */
        Route::controller(CampaignController::class)->prefix('campaigns')->group(function () {
            Route::get('', 'index')->name('admin.campaigns.index');

            Route::get('create', 'create')->name('admin.campaigns.create');

            Route::post('create', 'store')->name('admin.campaigns.store');

            Route::get('edit/{id}', 'edit')->name('admin.campaigns.edit');

            Route::post('edit/{id}', 'update')->name('admin.campaigns.update');

            Route::post('delete/{id}', 'destroy')->name('admin.campaigns.delete');
        });

        /**
         * Events routes.
         */
        Route::controller(EventController::class)->prefix('events')->group(function () {
            Route::get('', 'index')->name('admin.events.index');

            Route::get('create', 'create')->name('admin.events.create');

            Route::post('create', 'store')->name('admin.events.store');

            Route::get('edit/{id}', 'edit')->name('admin.events.edit');

            Route::post('edit/{id}', 'update')->name('admin.events.update');

            Route::post('delete/{id}', 'destroy')->name('admin.events.delete');
        });

        /**
         * subscribers routes.
         */
        Route::controller(SubscriptionController::class)->prefix('subscribers')->group(function () {
            Route::get('', 'index')->name('admin.customers.subscribers.index');

            Route::get('edit/{id}', 'edit')->name('admin.customers.subscribers.edit');

            Route::post('edit/{id}', 'update')->name('admin.customers.subscribers.update');

            Route::post('delete/{id}', 'destroy')->name('admin.customers.subscribers.delete');
        });

        /**
         * Emails templates routes.
         */
        Route::controller(TemplateController::class)->prefix('email-templates')->group(function () {
            Route::get('', 'index')->name('admin.email_templates.index');

            Route::get('create', 'create')->name('admin.email_templates.create');

            Route::post('create', 'store')->name('admin.email_templates.store');

            Route::get('edit/{id}', 'edit')->name('admin.email_templates.edit');

            Route::post('edit/{id}', 'update')->name('admin.email_templates.update');

            Route::post('delete/{id}', 'destroy')->name('admin.email_templates.delete');
        });

        /**
         * sitemaps routes.
         */
        Route::controller(SitemapController::class)->prefix('sitemaps')->group(function () {
            Route::get('', 'index')->name('admin.sitemaps.index');

            Route::get('create', 'create')->name('admin.sitemaps.create');

            Route::post('create', 'store')->name('admin.sitemaps.store');

            Route::get('edit/{id}', 'edit')->name('admin.sitemaps.edit');

            Route::post('edit/{id}', 'update')->name('admin.sitemaps.update');

            Route::post('delete/{id}', 'destroy')->name('admin.sitemaps.delete');
        });
    });
});
