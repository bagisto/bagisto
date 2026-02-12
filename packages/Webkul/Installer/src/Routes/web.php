<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Webkul\Installer\Http\Controllers\InstallerController;

Route::middleware([
    'installer_file_session',
    'web',
    'installer_locale',
])
    ->group(function () {
        Route::controller(InstallerController::class)->group(function () {
            Route::get('install', 'index')->name('installer.index');

            Route::prefix('install/api')
                ->withoutMiddleware([
                    VerifyCsrfToken::class,
                ])
                ->group(function () {
                    Route::post('run-migration', 'runMigration')->name('installer.run_migration');

                    Route::post('run-seeder', 'runSeeder')->name('installer.run_seeder');

                    Route::post('seed-sample-products', 'seedSampleProducts')->name('installer.seed_sample_products');

                    Route::post('create-admin-user', 'createAdminUser')->name('installer.create_admin_user');
                });
        });
    });
