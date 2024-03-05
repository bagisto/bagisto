<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\ConfigurationController;

/**
 * Configuration routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::get('configuration/search', [ConfigurationController::class, 'search'])->name('admin.configuration.search');

    Route::controller(ConfigurationController::class)->prefix('configuration/{slug?}/{slug2?}')->group(function () {

        Route::get('', 'index')->name('admin.configuration.index');

        Route::post('', 'store')->name('admin.configuration.store');

        Route::get('{path}', 'download')->defaults('_config', [
            'redirect' => 'admin.configuration.index',
        ])->name('admin.configuration.download');
    });
});
