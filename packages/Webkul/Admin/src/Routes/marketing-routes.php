<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Marketing\Communications\CampaignController;
use Webkul\Admin\Http\Controllers\Marketing\Communications\EventController;
use Webkul\Admin\Http\Controllers\Marketing\Communications\SubscriptionController;
use Webkul\Admin\Http\Controllers\Marketing\Communications\TemplateController;
use Webkul\Admin\Http\Controllers\Marketing\Promotions\CartRuleController;
use Webkul\Admin\Http\Controllers\Marketing\Promotions\CartRuleCouponController;
use Webkul\Admin\Http\Controllers\Marketing\Promotions\CatalogRuleController;
use Webkul\Admin\Http\Controllers\Marketing\SearchSEO\SearchSynonymController;
use Webkul\Admin\Http\Controllers\Marketing\SearchSEO\SearchTermController;
use Webkul\Admin\Http\Controllers\Marketing\SearchSEO\SitemapController;
use Webkul\Admin\Http\Controllers\Marketing\SearchSEO\URLRewriteController;

/**
 * Marketing routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('marketing')->group(function () {
        /**
         * Promotions routes.
         */
        Route::prefix('promotions')->group(function () {
            /**
             * Cart rules routes.
             */
            Route::controller(CartRuleController::class)->prefix('cart-rules')->group(function () {
                Route::get('', 'index')->name('admin.marketing.promotions.cart_rules.index');

                Route::get('create', 'create')->name('admin.marketing.promotions.cart_rules.create');

                Route::post('create', 'store')->name('admin.marketing.promotions.cart_rules.store');

                Route::get('copy/{id}', 'copy')->name('admin.marketing.promotions.cart_rules.copy');

                Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.cart_rules.edit');

                Route::put('edit/{id}', 'update')->name('admin.marketing.promotions.cart_rules.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.cart_rules.delete');
            });

            /**
             * Cart rule coupons routes.
             */
            Route::controller(CartRuleCouponController::class)->prefix('cart-rules/coupons')->group(function () {
                Route::post('mass-delete', 'massDestroy')->name('admin.marketing.promotions.cart_rules.coupons.mass_delete');

                Route::get('{id}', 'index')->name('admin.marketing.promotions.cart_rules.coupons.index');

                Route::post('{id}', 'store')->name('admin.marketing.promotions.cart_rules.coupons.store');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.cart_rules.coupons.delete');
            });

            /**
             * Catalog rules routes.
             */
            Route::controller(CatalogRuleController::class)->prefix('catalog-rules')->group(function () {
                Route::get('', 'index')->name('admin.marketing.promotions.catalog_rules.index');

                Route::get('create', 'create')->name('admin.marketing.promotions.catalog_rules.create');

                Route::post('create', 'store')->name('admin.marketing.promotions.catalog_rules.store');

                Route::get('edit/{id}', 'edit')->name('admin.marketing.promotions.catalog_rules.edit');

                Route::put('edit/{id}', 'update')->name('admin.marketing.promotions.catalog_rules.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.promotions.catalog_rules.delete');
            });
        });

        /**
         * Communication routes.
         */
        Route::prefix('communications')->group(function () {
            /**
             * Emails templates routes.
             */
            Route::controller(TemplateController::class)->prefix('email-templates')->group(function () {
                Route::get('', 'index')->name('admin.marketing.communications.email_templates.index');

                Route::get('create', 'create')->name('admin.marketing.communications.email_templates.create');

                Route::post('create', 'store')->name('admin.marketing.communications.email_templates.store');

                Route::get('edit/{id}', 'edit')->name('admin.marketing.communications.email_templates.edit');

                Route::put('edit/{id}', 'update')->name('admin.marketing.communications.email_templates.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.communications.email_templates.delete');
            });

            /**
             * Events routes.
             */
            Route::controller(EventController::class)->prefix('events')->group(function () {
                Route::get('', 'index')->name('admin.marketing.communications.events.index');

                Route::post('create', 'store')->name('admin.marketing.communications.events.store');

                Route::get('edit/{id}', 'edit')->name('admin.marketing.communications.events.edit');

                Route::put('edit', 'update')->name('admin.marketing.communications.events.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.communications.events.delete');
            });

            /**
             * Campaigns routes.
             */
            Route::controller(CampaignController::class)->prefix('campaigns')->group(function () {
                Route::get('', 'index')->name('admin.marketing.communications.campaigns.index');

                Route::get('create', 'create')->name('admin.marketing.communications.campaigns.create');

                Route::post('create', 'store')->name('admin.marketing.communications.campaigns.store');

                Route::get('edit/{id}', 'edit')->name('admin.marketing.communications.campaigns.edit');

                Route::put('edit/{id}', 'update')->name('admin.marketing.communications.campaigns.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.communications.campaigns.delete');
            });

            /**
             * subscribers routes.
             */
            Route::controller(SubscriptionController::class)->prefix('subscribers')->group(function () {
                Route::get('', 'index')->name('admin.marketing.communications.subscribers.index');

                Route::get('edit/{id}', 'edit')->name('admin.marketing.communications.subscribers.edit');

                Route::put('edit', 'update')->name('admin.marketing.communications.subscribers.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.communications.subscribers.delete');
            });
        });

        /**
         * Search and SEO routes.
         */
        Route::prefix('search-seo')->group(function () {
            /**
             * URL Rewrite routes.
             */
            Route::controller(URLRewriteController::class)->prefix('url-rewrites')->group(function () {
                Route::get('', 'index')->name('admin.marketing.search_seo.url_rewrites.index');

                Route::post('create', 'store')->name('admin.marketing.search_seo.url_rewrites.store');

                Route::put('edit', 'update')->name('admin.marketing.search_seo.url_rewrites.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.search_seo.url_rewrites.delete');

                Route::post('mass-delete', 'massDestroy')->name('admin.marketing.search_seo.url_rewrites.mass_delete');
            });

            /**
             * Search Terms routes.
             */
            Route::controller(SearchTermController::class)->prefix('search-terms')->group(function () {
                Route::get('', 'index')->name('admin.marketing.search_seo.search_terms.index');

                Route::post('create', 'store')->name('admin.marketing.search_seo.search_terms.store');

                Route::put('edit', 'update')->name('admin.marketing.search_seo.search_terms.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.search_seo.search_terms.delete');

                Route::post('mass-delete', 'massDestroy')->name('admin.marketing.search_seo.search_terms.mass_delete');
            });

            /**
             * Search Synonyms routes.
             */
            Route::controller(SearchSynonymController::class)->prefix('search-synonyms')->group(function () {
                Route::get('', 'index')->name('admin.marketing.search_seo.search_synonyms.index');

                Route::post('create', 'store')->name('admin.marketing.search_seo.search_synonyms.store');

                Route::put('edit', 'update')->name('admin.marketing.search_seo.search_synonyms.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.search_seo.search_synonyms.delete');

                Route::post('mass-delete', 'massDestroy')->name('admin.marketing.search_seo.search_synonyms.mass_delete');
            });

            /**
             * Sitemaps routes.
             */
            Route::controller(SitemapController::class)->prefix('sitemaps')->group(function () {
                Route::get('', 'index')->name('admin.marketing.search_seo.sitemaps.index');

                Route::post('create', 'store')->name('admin.marketing.search_seo.sitemaps.store');

                Route::put('edit', 'update')->name('admin.marketing.search_seo.sitemaps.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.marketing.search_seo.sitemaps.delete');
            });
        });
    });
});
