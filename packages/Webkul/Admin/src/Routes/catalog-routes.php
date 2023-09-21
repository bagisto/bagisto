<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Catalog\CategoryController;
use Webkul\Admin\Http\Controllers\Catalog\AttributeFamilyController;
use Webkul\Admin\Http\Controllers\Catalog\AttributeController;
use Webkul\Admin\Http\Controllers\Catalog\ProductController;

/**
 * Catalog routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('catalog')->group(function () {
        /**
         * Attributes routes.
         */
        Route::controller(AttributeController::class)->prefix('attributes')->group(function () {
            Route::get('', 'index')->name('admin.catalog.attributes.index');

            Route::get('{id}/options', 'getAttributeOptions')->name('admin.catalog.attributes.options');

            Route::get('create', 'create')->name('admin.catalog.attributes.create');

            Route::post('create', 'store')->name('admin.catalog.attributes.store');

            Route::get('edit/{id}', 'edit')->name('admin.catalog.attributes.edit');

            Route::put('edit/{id}', 'update')->name('admin.catalog.attributes.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.catalog.attributes.delete');

            Route::post('mass-delete', 'massDestroy')->name('admin.catalog.attributes.mass_delete');

        });

        /**
         * Attribute families routes.
         */
        Route::controller(AttributeFamilyController::class)->prefix('families')->group(function () {
            Route::get('', 'index')->name('admin.catalog.families.index');

            Route::get('create', 'create')->name('admin.catalog.families.create');

            Route::post('create', 'store')->name('admin.catalog.families.store');

            Route::get('edit/{id}', 'edit')->name('admin.catalog.families.edit');

            Route::put('edit/{id}', 'update')->name('admin.catalog.families.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.catalog.families.delete');
        });

        /**
         * Categories routes.
         */
        Route::controller(CategoryController::class)->prefix('categories')->group(function () {
            Route::get('', 'index')->name('admin.catalog.categories.index');

            Route::get('create', 'create')->name('admin.catalog.categories.create');

            Route::post('create', 'store')->name('admin.catalog.categories.store');

            Route::get('edit/{id}', 'edit')->name('admin.catalog.categories.edit');

            Route::put('edit/{id}', 'update')->name('admin.catalog.categories.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.catalog.categories.delete');

            Route::get('products/{id}', 'products')->name('admin.catalog.categories.products');

            Route::post('mass-delete', 'massDestroy')->name('admin.catalog.categories.mass_delete');

            Route::post('mass-update', 'massUpdate')->name('admin.catalog.categories.mass_update');

            Route::get('search', 'search')->name('admin.catalog.categories.search');
            
            Route::get('tree', 'tree')->name('admin.catalog.categories.tree');
        });

        /**
         * Sync route.
         */
        Route::get('/sync', [ProductController::class, 'sync']);

        /**
         * Products routes.
         */
        Route::controller(ProductController::class)->prefix('products')->group(function () {
            Route::get('', 'index')->name('admin.catalog.products.index');

            Route::get('create', 'create')->name('admin.catalog.products.create');

            Route::post('create', 'store')->name('admin.catalog.products.store');

            Route::get('copy/{id}', 'copy')->name('admin.catalog.products.copy');

            Route::get('edit/{id}', 'edit')->name('admin.catalog.products.edit');

            Route::put('edit/{id}', 'update')->name('admin.catalog.products.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.catalog.products.delete');

            Route::put('edit/{id}/inventories', 'updateInventories')->name('admin.catalog.products.update_inventories');

            Route::post('upload-file/{id}', 'uploadLink')->name('admin.catalog.products.upload_link');

            Route::post('upload-sample/{id}', 'uploadSample')->name('admin.catalog.products.upload_sample');

            Route::post('mass-action', 'massUpdate')->name('admin.catalog.products.mass_action');

            Route::post('mass-update', 'massUpdate')->name('admin.catalog.products.mass_update');
            
            Route::post('mass-delete', 'massDestroy')->name('admin.catalog.products.mass_delete');

            Route::get('search', 'search')->name('admin.catalog.products.search');

            Route::get('{id}/{attribute_id}', 'download')->defaults('_config', [
                'view' => 'admin.catalog.products.edit',
            ])->name('admin.catalog.products.file.download');
        });
    });
});
