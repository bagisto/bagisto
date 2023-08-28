<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Marketing\CartRuleController;
use Webkul\Admin\Http\Controllers\Marketing\CartRuleCouponController;
use Webkul\Admin\Http\Controllers\Marketing\CatalogRuleController;
use Webkul\Admin\Http\Controllers\Marketing\CampaignController;
use Webkul\Admin\Http\Controllers\Marketing\EventController;
use Webkul\Admin\Http\Controllers\Marketing\SubscriptionController;
use Webkul\Admin\Http\Controllers\Marketing\TemplateController;
use Webkul\Admin\Http\Controllers\Marketing\SitemapController;

/**
 * Marketing routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('marketing/promotions')->group(function () {
        /**
         * Cart rules routes.
         */
        Route::controller(CartRuleController::class)->prefix('cart-rules')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.cart_rules.index');

            Route::get('create', 'create')->name('admin.marketing.promotions.cart_rules.create');

            Route::post('create', 'store')->name('admin.marketing.promotions.cart_rules.store');

            Route::get('copy/{id}', 'copy')->name('admin.marketing.promotions.cart_rules.copy');

            Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.cart_rules.edit');

            Route::post('edit/{id}', 'update')->name('admin.marketing.promotions.cart_rules.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.cart_rules.delete');
        });

        /**
         * Cart rule coupons routes.
         */
        Route::controller(CartRuleCouponController::class)->prefix('cart-rules/coupons')->group(function () {
            Route::get('{id}', 'index')->name('admin.marketing.promotions.cart_rules.coupons.index');

            Route::post('{id}', 'store')->name('adadmin.marketing.promotions.cart_rules.coupons.store');

            Route::post('mass-delete', 'massDelete')->name('adadmin.marketing.promotions.cart_rules.coupons.mass_delete');
        });

        /**
         * Catalog rules routes.
         */
        Route::controller(CatalogRuleController::class)->prefix('catalog-rules')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.catalog_rules.index');

            Route::get('create', 'create')->name('admin.marketing.promotions.catalog_rules.create');

            Route::post('create', 'store')->name('admin.marketing.promotions.catalog_rules.store');

            Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.catalog_rules.edit');

            Route::post('edit/{id}', 'update')->name('admin.marketing.promotions.catalog_rules.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.catalog_rules.delete');
        });

        /**
         * Campaigns routes.
         */
        Route::controller(CampaignController::class)->prefix('campaigns')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.campaigns.index');

            Route::get('create', 'create')->name('admin.marketing.promotions.campaigns.create');

            Route::post('create', 'store')->name('admin.marketing.promotions.campaigns.store');

            Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.campaigns.edit');

            Route::post('edit/{id}', 'update')->name('admin.marketing.promotions.campaigns.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.campaigns.delete');
        });

        /**
         * Events routes.
         */
        Route::controller(EventController::class)->prefix('events')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.events.index');

            Route::post('create', 'store')->name('admin.marketing.promotions.events.store');

            Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.events.edit');

            Route::post('edit', 'update')->name('admin.marketing.promotions.events.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.events.delete');
        });

        /**
         * subscribers routes.
         */
        Route::controller(SubscriptionController::class)->prefix('subscribers')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.customers.subscribers.index');

            Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.customers.subscribers.edit');

            Route::post('edit', 'update')->name('admin.marketing.promotions.customers.subscribers.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.customers.subscribers.delete');
        });

        /**
         * Emails templates routes.
         */
        Route::controller(TemplateController::class)->prefix('email-templates')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.email_templates.index');

            Route::get('create', 'create')->name('admin.marketing.promotions.email_templates.create');

            Route::post('create', 'store')->name('admin.marketing.promotions.email_templates.store');

            Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.email_templates.edit');

            Route::post('edit/{id}', 'update')->name('admin.marketing.promotions.email_templates.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.email_templates.delete');
        });

        /**
         * sitemaps routes.
         */
        Route::controller(SitemapController::class)->prefix('sitemaps')->group(function () {
            Route::get('', 'index')->name('admin.marketing.promotions.sitemaps.index');

            Route::post('create', 'store')->name('admin.marketing.promotions.sitemaps.store');

            Route::post('edit', 'update')->name('admin.marketing.promotions.sitemaps.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.sitemaps.delete');
        });
    });
});
