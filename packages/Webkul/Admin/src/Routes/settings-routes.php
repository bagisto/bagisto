<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Settings\ChannelController;
use Webkul\Admin\Http\Controllers\Settings\CurrencyController;
use Webkul\Admin\Http\Controllers\Settings\ExchangeRateController;
use Webkul\Admin\Http\Controllers\Settings\LocaleController;
use Webkul\Admin\Http\Controllers\Settings\InventorySourceController;
use Webkul\Admin\Http\Controllers\Settings\Tax\TaxCategoryController;
use Webkul\Admin\Http\Controllers\Settings\Tax\TaxRateController;
use Webkul\Admin\Http\Controllers\Settings\ThemeController;
use Webkul\Admin\Http\Controllers\Settings\RoleController;
use Webkul\Admin\Http\Controllers\Settings\UserController;

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

            Route::post('create', 'store')->name('admin.settings.currencies.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.currencies.edit');

            Route::put('edit', 'update')->name('admin.settings.currencies.update');

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

            Route::put('edit', 'update')->name('admin.settings.exchange_rates.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.exchange_rates.delete');
        });

        /**
         * Locales routes.
         */
        Route::controller(LocaleController::class)->prefix('locales')->group(function () {
            Route::get('', 'index')->name('admin.settings.locales.index');

            Route::post('create', 'store')->name('admin.settings.locales.store');

            Route::get('edit/{id}', 'edit')->name('admin.settings.locales.edit');

            Route::put('edit', 'update')->name('admin.settings.locales.update');

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
            Route::controller(TaxCategoryController::class)->prefix('categories')->group(function () {
                Route::get('', 'index')->name('admin.settings.taxes.categories.index');

                Route::post('', 'store')->name('admin.settings.taxes.categories.store');

                Route::get('edit/{id}', 'edit')->name('admin.settings.taxes.categories.edit');

                Route::put('edit', 'update')->name('admin.settings.taxes.categories.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.settings.taxes.categories.delete');
            });

            /**
             * Tax rates routes.
             */
            Route::controller(TaxRateController::class)->prefix('rates')->group(function () {
                Route::get('', 'index')->name('admin.settings.taxes.rates.index');

                Route::get('create', 'show')->name('admin.settings.taxes.rates.create');

                Route::post('create', 'create')->name('admin.settings.taxes.rates.store');

                Route::get('edit/{id}', 'edit')->name('admin.settings.taxes.rates.edit');

                Route::put('edit/{id}', 'update')->name('admin.settings.taxes.rates.update');

                Route::delete('edit/{id}', 'destroy')->name('admin.settings.taxes.rates.delete');

                Route::post('import', 'import')->name('admin.settings.taxes.rates.import');
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

            Route::put('edit', 'update')->name('admin.settings.users.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.settings.users.delete');

            Route::put('confirm', 'destroySelf')->name('admin.settings.users.destroy');
        });
    });

    Route::controller(ThemeController::class)->prefix('settings/themes')->group(function () {
        Route::get('', 'index')->name('admin.settings.themes.index');

        Route::get('edit/{id}', 'edit')->name('admin.settings.themes.edit');

        Route::post('store', 'store')->name('admin.settings.themes.store');

        Route::post('edit/{id}', 'update')->name('admin.settings.themes.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.settings.themes.delete');
    });
});
