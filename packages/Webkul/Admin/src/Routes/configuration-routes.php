<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\ConfigurationController;

/**
 * Configuration routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::get('configuration/{slug?}/{slug2?}', [ConfigurationController::class, 'index'])->defaults('_config', [
        'view' => 'admin::configuration.index',
    ])->name('admin.configuration.index');

    Route::post('configuration/{slug?}/{slug2?}', [ConfigurationController::class, 'store'])->defaults('_config', [
        'redirect' => 'admin.configuration.index',
    ])->name('admin.configuration.index.store');

    Route::get('configuration/{slug?}/{slug2?}/{path}', [ConfigurationController::class, 'download'])->defaults('_config', [
        'redirect' => 'admin.configuration.index',
    ])->name('admin.configuration.download');
});
