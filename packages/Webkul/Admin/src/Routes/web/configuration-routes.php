<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\CacheManagementController;
use Webkul\Admin\Http\Controllers\ConfigurationController;
use Webkul\Admin\Http\Controllers\SearchEngineController;
use Webkul\Admin\Http\Controllers\StorageSyncController;

/**
 * Configuration routes.
 */
Route::get('configuration/search', [ConfigurationController::class, 'search'])
    ->name('admin.configuration.search');

Route::post('configuration/cache-management/execute', [CacheManagementController::class, 'execute'])
    ->name('admin.configuration.cache_management.execute');

Route::post('configuration/search-engines/{engine}/test-connection', [SearchEngineController::class, 'testConnection'])
    ->name('admin.configuration.search_engines.test_connection');

Route::get('configuration/file-management/sync', [StorageSyncController::class, 'status'])
    ->name('admin.configuration.file_management.sync_status');

Route::post('configuration/file-management/sync', [StorageSyncController::class, 'sync'])
    ->name('admin.configuration.file_management.sync');

Route::controller(ConfigurationController::class)->prefix('configuration/{slug?}/{slug2?}')->group(function () {
    Route::get('', 'index')->name('admin.configuration.index');

    Route::post('', 'store')->name('admin.configuration.store');

    Route::get('{path}', 'download')->name('admin.configuration.download');
});
