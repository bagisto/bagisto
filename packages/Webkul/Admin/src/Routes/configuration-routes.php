<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\CacheManagementController;
use Webkul\Admin\Http\Controllers\ConfigurationController;
use Webkul\Admin\Http\Controllers\SearchEngineController;

/**
 * Configuration routes.
 */
Route::get('configuration/search', [ConfigurationController::class, 'search'])
    ->name('admin.configuration.search');

Route::post('configuration/cache-management/execute', [CacheManagementController::class, 'execute'])
    ->name('admin.configuration.cache-management.execute');

Route::post('configuration/search-engines/{engine}/test-connection', [SearchEngineController::class, 'testConnection'])
    ->name('admin.configuration.search-engines.test-connection');

Route::controller(ConfigurationController::class)->prefix('configuration/{slug?}/{slug2?}')->group(function () {
    Route::get('', 'index')->name('admin.configuration.index');

    Route::post('', 'store')->name('admin.configuration.store');

    Route::get('{path}', 'download')->name('admin.configuration.download');
});
