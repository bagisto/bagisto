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
    /**
     * Channels routes.
     */
    Route::controller(ChannelController::class)->prefix('channels')->group(function () {
        Route::get('', 'index')->name('admin.channels.index');

        Route::get('create', 'create')->name('admin.channels.create');

        Route::post('create', 'store')->name('admin.channels.store');

        Route::get('edit/{id}', 'edit')->name('admin.channels.edit');

        Route::put('edit/{id}', 'update')->name('admin.channels.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.channels.delete');
    });

    /**
     * Currencies routes.
     */
    Route::controller(CurrencyController::class)->prefix('currencies')->group(function () {
        Route::get('', 'index')->name('admin.currencies.index');

        Route::get('create', 'create')->name('admin.currencies.create');

        Route::post('create', 'store')->name('admin.currencies.store');

        Route::get('edit/{id}', 'edit')->name('admin.currencies.edit');

        Route::post('edit', 'update')->name('admin.currencies.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.currencies.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.currencies.mass_delete');
    });

    /**
     * Exchange rates routes.
     */
    Route::controller(ExchangeRateController::class)->prefix('exchange-rates')->group(function () {
        Route::get('', 'index')->name('admin.exchange_rates.index');

        Route::post('create', 'store')->name('admin.exchange_rates.store');

        Route::get('edit/{id}', 'edit')->name('admin.exchange_rates.edit');

        Route::get('update-rates', 'updateRates')->name('admin.exchange_rates.update_rates');

        Route::post('edit', 'update')->name('admin.exchange_rates.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.exchange_rates.delete');
    });

    /**
     * Locales routes.
     */
    Route::controller(LocaleController::class)->prefix('locales')->group(function () {
        Route::get('', 'index')->name('admin.locales.index');

        Route::get('create', 'create')->name('admin.locales.create');

        Route::post('create', 'store')->name('admin.locales.store');

        Route::get('edit/{id}', 'edit')->name('admin.locales.edit');

        Route::post('edit', 'update')->name('admin.locales.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.locales.delete');
    });

    /**
     * Inventory sources routes.
     */
    Route::controller(InventorySourceController::class)->prefix('inventory-sources')->group(function () {
        Route::get('', 'index')->name('admin.inventory_sources.index');

        Route::get('create', 'create')->name('admin.inventory_sources.create');

        Route::post('create', 'store')->name('admin.inventory_sources.store');

        Route::get('edit/{id}', 'edit')->name('admin.inventory_sources.edit');

        Route::put('edit/{id}', 'update')->name('admin.inventory_sources.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.inventory_sources.delete');
    });

    /**
     * Tax categories routes.
     */
    Route::controller(TaxCategoryController::class)->prefix('tax-categories')->group(function () {
        Route::get('', 'index')->name('admin.tax_categories.index');

        Route::post('', 'store')->name('admin.tax_categories.store');

        Route::get('edit/{id}', 'edit')->name('admin.tax_categories.edit');

        Route::post('edit', 'update')->name('admin.tax_categories.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.tax_categories.delete');
    });

    /**
     * Tax rates routes.
     */
    Route::controller(TaxRateController::class)->prefix('tax-rates')->group(function () {
        Route::get('', 'index')->name('admin.tax_rates.index');

        Route::get('create', 'show')->name('admin.tax_rates.create');

        Route::post('create', 'create')->name('admin.tax_rates.store');

        Route::get('edit/{id}', 'edit')->name('admin.tax_rates.edit');

        Route::put('edit/{id}', 'update')->name('admin.tax_rates.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.tax_rates.delete');

        Route::post('import', 'import')->name('admin.tax_rates.import');
    });

    /**
     * Roles routes.
     */
    Route::controller(RoleController::class)->prefix('roles')->group(function () {
        Route::get('', 'index')->name('admin.roles.index');

        Route::get('create', 'create')->name('admin.roles.create');

        Route::post('create', 'store')->name('admin.roles.store');

        Route::get('edit/{id}', 'edit')->name('admin.roles.edit');

        Route::put('edit/{id}', 'update')->name('admin.roles.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.roles.delete');
    });

    /**
     * Users routes.
     */
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('', 'index')->name('admin.users.index');

        Route::post('create', 'store')->name('admin.users.store');

        Route::get('edit/{id}', 'edit')->name('admin.users.edit');

        Route::post('edit', 'update')->name('admin.users.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.users.delete');

        Route::get('confirm/{id}', 'confirm')->name('super.users.confirm');

        Route::post('confirm/{id}', 'destroySelf')->name('admin.users.destroy');
    });


    Route::controller(ThemeController::class)->prefix('themes')->group(function () {
        Route::get('', 'index')->name('admin.theme.index');

        Route::get('all', 'getThemes')->name('admin.theme.themes');

        Route::post('create', 'store')->name('admin.theme.store');
    });
});
