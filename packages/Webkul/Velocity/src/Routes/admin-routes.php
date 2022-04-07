<?php

use Illuminate\Support\Facades\Route;
use Webkul\Velocity\Http\Controllers\Admin\ConfigurationController;
use Webkul\Velocity\Http\Controllers\Admin\ContentController;

Route::group(['middleware' => ['web']], function () {
    Route::prefix(config('app.admin_url') . '/velocity')->group(function () {
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/content', [ContentController::class, 'index'])->defaults('_config', [
                'view' => 'velocity::admin.content.index',
            ])->name('velocity.admin.content.index');

            Route::get('/content/search', [ContentController::class, 'search'])->name('velocity.admin.content.search');

            Route::get('/content/create', [ContentController::class, 'create'])->defaults('_config', [
                'view' => 'velocity::admin.content.create',
            ])->name('velocity.admin.content.create');

            Route::post('/content/create', [ContentController::class, 'store'])->defaults('_config', [
                'redirect' => 'velocity.admin.content.index',
            ])->name('velocity.admin.content.store');

            Route::get('/content/edit/{id}', [ContentController::class, 'edit'])->defaults('_config', [
                'view' => 'velocity::admin.content.edit',
            ])->name('velocity.admin.content.edit');

            Route::put('/content/edit/{id}', [ContentController::class, 'update'])->defaults('_config', [
                'redirect' => 'velocity.admin.content.index',
            ])->name('velocity.admin.content.update');

            Route::post('/content/delete/{id}', [ContentController::class, 'destroy'])->name('velocity.admin.content.delete');

            Route::post('/content/masssdelete', [ContentController::class, 'massDestroy'])->defaults('_config', [
                'redirect' => 'velocity.admin.content.index',
            ])->name('velocity.admin.content.mass-delete');

            Route::post('/content/masss-update', [ContentController::class, 'massUpdate'])->defaults('_config', [
                'redirect' => 'velocity.admin.content.index',
            ])->name('velocity.admin.content.mass-update');

            Route::get('/meta-data', [ConfigurationController::class, 'renderMetaData'])->defaults('_config', [
                'view' => 'velocity::admin.meta-info.meta-data',
            ])->name('velocity.admin.meta-data');

            Route::post('/meta-data/{id}', [ConfigurationController::class, 'storeMetaData'])->defaults('_config', [
                'redirect' => 'velocity.admin.meta-data',
            ])->name('velocity.admin.store.meta-data');
        });
    });
});
