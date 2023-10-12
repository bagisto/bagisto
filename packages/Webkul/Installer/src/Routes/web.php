<?php

use Illuminate\Support\Facades\Route;

Route::get('install', [Webkul\Installer\Http\Controllers\InstallerController::class, 'index'])->name('installer.index');

Route::middleware(Illuminate\Session\Middleware\StartSession::class)->prefix('install/api')->group(function () {
    Route::post('env-file-setup', [
        'uses' => 'Webkul\Installer\Http\Controllers\InstallerController@envFileSetup',
        'as'   => 'installer.envFileSetup',
    ]);

    Route::post('admin-config-setup', [
        'uses' => 'Webkul\Installer\Http\Controllers\InstallerController@adminConfigSetup',
        'as'   => 'installer.adminConfigSetup',
    ]);

    Route::post('smtp-config-setup', [
        'uses' => 'Webkul\Installer\Http\Controllers\InstallerController@smtpConfigSetup',
        'as'   => 'installer.smtpConfigSetup',
    ]);
});
