<?php

use Illuminate\Support\Facades\Route;
use Webkul\Installer\Http\Controllers\InstallerController;


Route::controller(InstallerController::class)->group(function () {
    Route::get('install', 'index')->name('installer.index');

    Route::middleware(Illuminate\Session\Middleware\StartSession::class)->prefix('install/api')->group(function () {
        Route::post('env-file-setup', 'envFileSetup')->name('installer.envFileSetup');
        
        Route::post('env-file-delete', 'envFileDelete')->name('installer.deleteEnvFile');
        
        Route::post('run-migration', 'runMigration')->name('installer.runMigration');
    
        Route::post('admin-config-setup', 'adminConfigSetup')->name('installer.adminConfigSetup');
    
        Route::post('smtp-config-setup', 'smtpConfigSetup')->name('installer.smtpConfigSetup');
    });
});