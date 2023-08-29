<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Settings\ChannelController;
use Webkul\Admin\Http\Controllers\Settings\CurrencyController;
use Webkul\Admin\Http\Controllers\Settings\ExchangeRateController;
use Webkul\Admin\Http\Controllers\Settings\LocaleController;
use Webkul\Admin\Http\Controllers\Settings\InventorySourceController;
use Webkul\Admin\Http\Controllers\Settings\TaxCategoryController;
use Webkul\Admin\Http\Controllers\Settings\TaxRateController;
use Webkul\Admin\Http\Controllers\Settings\ThemeController;
use Webkul\Admin\Http\Controllers\User\RoleController;
use Webkul\Admin\Http\Controllers\User\UserController;

/**
 * Settings routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('settings')->group(function () {
        /**
         * Channels routes.
         */
        Route::controller(ChannelController::class)->prefix('channels')->group(function () {
            Route::get('', 'index')->name('admin.settings.channels.index');

            Route::get('create', 'create')->name('admin.settings.channels.create');

            Route::post('create', 'store')->name('admin.settings.channels.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.channels.edit');

            Route::put('edit/{id}', 'update')->name('admin.settings.channels.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.channels.delete');
        });

        /**
         * Currencies routes.
         */
        Route::controller(CurrencyController::class)->prefix('currencies')->group(function () {
            Route::get('', 'index')->name('admin.settings.currencies.index');

            Route::get('create', 'create')->name('admin.settings.currencies.create');

            Route::post('create', 'store')->name('admin.settings.currencies.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.currencies.edit');

            Route::post('edit', 'update')->name('admin.settings.currencies.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.currencies.delete');

            Route::post('mass-delete', 'massDestroy')->name('admin.settings.currencies.mass_delete');
        });

        /**
         * Exchange rates routes.
         */
        Route::controller(ExchangeRateController::class)->prefix('exchange-rates')->group(function () {
            Route::get('', 'index')->name('admin.settings.exchange_rates.index');

            Route::post('create', 'store')->name('admin.settings.exchange_rates.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.exchange_rates.edit');

            Route::get('update-rates', 'updateRates')->name('admin.settings.exchange_rates.update_rates');

            Route::post('edit', 'update')->name('admin.settings.exchange_rates.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.exchange_rates.delete');
        });

        /**
         * Locales routes.
         */
        Route::controller(LocaleController::class)->prefix('locales')->group(function () {
            Route::get('', 'index')->name('admin.settings.locales.index');

            Route::get('create', 'create')->name('admin.settings.locales.create');

            Route::post('create', 'store')->name('admin.settings.locales.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.locales.edit');

            Route::post('edit', 'update')->name('admin.settings.locales.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.locales.delete');
        });

        /**
         * Inventory sources routes.
         */
        Route::controller(InventorySourceController::class)->prefix('inventory-sources')->group(function () {
            Route::get('', 'index')->name('admin.settings.inventory_sources.index');

            Route::get('create', 'create')->name('admin.settings.inventory_sources.create');

            Route::post('create', 'store')->name('admin.settings.inventory_sources.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.inventory_sources.edit');

            Route::put('edit/{id}', 'update')->name('admin.settings.inventory_sources.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.inventory_sources.delete');
        });

        Route::prefix('taxes')->group(function () {
            /**
             * Tax categories routes.
             */
            Route::controller(TaxCategoryController::class)->prefix('tax-categories')->group(function () {
                Route::get('', 'index')->name('admin.settings.taxes.tax_categories.index');

                Route::post('', 'store')->name('admin.settings.taxes.tax_categories.store');

                Route::get('edit/{id}', 'edit')->name('admin.settings.taxes.tax_categories.edit');

                Route::post('edit', 'update')->name('admin.settings.taxes.tax_categories.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.settings.taxes.tax_categories.delete');
            });

            /**
             * Tax rates routes.
             */
            Route::controller(TaxRateController::class)->prefix('tax-rates')->group(function () {
                Route::get('', 'index')->name('admin.settings.taxes.tax_rates.index');

                Route::get('create', 'show')->name('admin.settings.taxes.tax_rates.create');

                Route::post('create', 'create')->name('admin.settings.taxes.tax_rates.store');

                Route::get('edit/{id}', 'edit')->name('admin.settings.taxes.tax_rates.edit');

                Route::put('edit/{id}', 'update')->name('admin.settings.taxes.tax_rates.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.settings.taxes.tax_rates.delete');

                Route::post('import', 'import')->name('admin.settings.taxes.tax_rates.import');
            });
        });

        /**
         * Roles routes.
         */
        Route::controller(RoleController::class)->prefix('roles')->group(function () {
            Route::get('', 'index')->name('admin.settings.roles.index');

            Route::get('create', 'create')->name('admin.settings.roles.create');

            Route::post('create', 'store')->name('admin.settings.roles.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.roles.edit');

            Route::put('edit/{id}', 'update')->name('admin.settings.roles.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.roles.delete');
        });

        /**
         * Users routes.
         */
        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::get('', 'index')->name('admin.settings.users.index');

            Route::post('create', 'store')->name('admin.settings.users.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.users.edit');

            Route::post('edit', 'update')->name('admin.settings.users.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.users.delete');

            Route::get('confirm/{id}', 'confirm')->name('super.settings.users.confirm');

            Route::post('confirm/{id}', 'destroySelf')->name('admin.settings.users.destroy');
        });
    });


    Route::controller(ThemeController::class)->prefix('settings/themes')->group(function () {
        Route::get('', 'index')->name('admin.theme.index');

        Route::get('all', 'getThemes')->name('admin.theme.themes');

        /**
         * Static Content related routes
         */
        Route::post('create-static-content', 'storeStaticContent')->name('admin.theme.store_static_content');

        Route::post('edit-static-content/{id}', 'updateStaticContent')->name('admin.theme.update_static_content');

        Route::delete('edit-static_content/{id}', 'destroyStaticContent')->name('admin.theme.delete_static_content');


        /**
         * Product and Category Carousels related routes
         */
        Route::post('store-product-and-category-carousel', 'storeProductAndCategoryCarousel')->name('admin.theme.store_product_and_category_carousel');

        Route::post('edit-product-and-category-carousel/{id}', 'updateProductAndCategoryCarousel')->name('admin.theme.update_product_and_category_carousel');

        Route::delete('edit-product-and-category-carousel/{id}', 'destroyProductAndCategoryCarousel')->name('admin.theme.delete_product_and_category_carousel');
    });
});
