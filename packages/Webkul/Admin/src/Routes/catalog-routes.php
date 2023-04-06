<?php

use Illuminate\Support\Facades\Route;
use Webkul\Attribute\Http\Controllers\AttributeController;
use Webkul\Attribute\Http\Controllers\AttributeFamilyController;
use Webkul\Category\Http\Controllers\CategoryController;
use Webkul\Product\Http\Controllers\ProductController;

/**
 * Catalog routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    Route::prefix('catalog')->group(function () {
        /**
         * Sync route.
         */
        Route::get('/sync', [ProductController::class, 'sync']);

        /**
         * Products routes.
         */
        Route::get('products', [ProductController::class, 'index'])->defaults('_config', [
            'view' => 'admin::catalog.products.index',
        ])->name('admin.catalog.products.index');

        Route::get('products/create', [ProductController::class, 'create'])->defaults('_config', [
            'view' => 'admin::catalog.products.create',
        ])->name('admin.catalog.products.create');

        Route::post('products/create', [ProductController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.products.edit',
        ])->name('admin.catalog.products.store');

        Route::get('products/copy/{id}', [ProductController::class, 'copy'])->defaults('_config', [
            'view' => 'admin::catalog.products.edit',
        ])->name('admin.catalog.products.copy');

        Route::get('products/edit/{id}', [ProductController::class, 'edit'])->defaults('_config', [
            'view' => 'admin::catalog.products.edit',
        ])->name('admin.catalog.products.edit');

        Route::put('products/edit/{id}', [ProductController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.products.index',
        ])->name('admin.catalog.products.update');

        Route::put('products/edit/{id}/inventories', [ProductController::class, 'updateInventories'])->defaults('_config', [
            'redirect' => 'admin.catalog.products.index',
        ])->name('admin.catalog.products.update_inventories');

        Route::post('products/upload-file/{id}', [ProductController::class, 'uploadLink'])->name('admin.catalog.products.upload_link');

        Route::post('products/upload-sample/{id}', [ProductController::class, 'uploadSample'])->name('admin.catalog.products.upload_sample');

        Route::post('products/delete/{id}', [ProductController::class, 'destroy'])->name('admin.catalog.products.delete');

        Route::post('products/mass-action', [ProductController::class, 'massActionHandler'])->name('admin.catalog.products.mass_action');

        Route::post('products/mass-delete', [ProductController::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.catalog.products.index',
        ])->name('admin.catalog.products.mass_delete');

        Route::post('products/mass-update', [ProductController::class, 'massUpdate'])->defaults('_config', [
            'redirect' => 'admin.catalog.products.index',
        ])->name('admin.catalog.products.mass_update');

        Route::get('products/search', [ProductController::class, 'productLinkSearch'])->name('admin.catalog.products.product_link_search');

        Route::get('products/search-simple-products', [ProductController::class, 'searchSimpleProducts'])->name('admin.catalog.products.search_simple_product');

        Route::get('products/{id}/{attribute_id}', [ProductController::class, 'download'])->defaults('_config', [
            'view' => 'admin.catalog.products.edit',
        ])->name('admin.catalog.products.file.download');

        /**
         * Categories routes.
         */
        Route::get('categories', [CategoryController::class, 'index'])->defaults('_config', [
            'view' => 'admin::catalog.categories.index',
        ])->name('admin.catalog.categories.index');

        Route::get('categories/create', [CategoryController::class, 'create'])->defaults('_config', [
            'view' => 'admin::catalog.categories.create',
        ])->name('admin.catalog.categories.create');

        Route::post('categories/create', [CategoryController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.store');

        Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->defaults('_config', [
            'view' => 'admin::catalog.categories.edit',
        ])->name('admin.catalog.categories.edit');

        Route::put('categories/edit/{id}', [CategoryController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.update');

        Route::get('categories/products/{id}', [CategoryController::class, 'products'])->name('admin.catalog.categories.products');

        Route::post('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.catalog.categories.delete');

        Route::post('categories/mass-delete', [CategoryController::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.mass_delete');

        Route::post('categories/mass-update', [CategoryController::class, 'massUpdate'])->defaults('_config', [
            'redirect' => 'admin.catalog.categories.index',
        ])->name('admin.catalog.categories.mass_update');

        Route::post('categories/product/count', [CategoryController::class, 'categoryProductCount'])->name('admin.catalog.categories.product.count');

        /**
         * Attributes routes.
         */
        Route::get('attributes', [AttributeController::class, 'index'])->defaults('_config', [
            'view' => 'admin::catalog.attributes.index',
        ])->name('admin.catalog.attributes.index');

        Route::get('attributes/{id}/options', [AttributeController::class, 'getAttributeOptions'])->defaults('_config', [
            'view' => 'admin::catalog.attributes.options',
        ])->name('admin.catalog.attributes.options');

        Route::get('attributes/create', [AttributeController::class, 'create'])->defaults('_config', [
            'view' => 'admin::catalog.attributes.create',
        ])->name('admin.catalog.attributes.create');

        Route::post('attributes/create', [AttributeController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.attributes.index',
        ])->name('admin.catalog.attributes.store');

        Route::get('attributes/edit/{id}', [AttributeController::class, 'edit'])->defaults('_config', [
            'view' => 'admin::catalog.attributes.edit',
        ])->name('admin.catalog.attributes.edit');

        Route::put('attributes/edit/{id}', [AttributeController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.attributes.index',
        ])->name('admin.catalog.attributes.update');

        Route::post('attributes/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.catalog.attributes.delete');

        Route::post('attributes/mass-delete', [AttributeController::class, 'massDestroy'])->name('admin.catalog.attributes.mass_delete');

        Route::get('attributes/products/{productId}/super-attributes', [AttributeController::class, 'productSuperAttributes'])->name('admin.catalog.product.super-attributes');

        /**
         * Attribute families routes.
         */
        Route::get('families', [AttributeFamilyController::class, 'index'])->defaults('_config', [
            'view' => 'admin::catalog.families.index',
        ])->name('admin.catalog.families.index');

        Route::get('families/create', [AttributeFamilyController::class, 'create'])->defaults('_config', [
            'view' => 'admin::catalog.families.create',
        ])->name('admin.catalog.families.create');

        Route::post('families/create', [AttributeFamilyController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.catalog.families.index',
        ])->name('admin.catalog.families.store');

        Route::get('families/edit/{id}', [AttributeFamilyController::class, 'edit'])->defaults('_config', [
            'view' => 'admin::catalog.families.edit',
        ])->name('admin.catalog.families.edit');

        Route::put('families/edit/{id}', [AttributeFamilyController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.catalog.families.index',
        ])->name('admin.catalog.families.update');

        Route::post('families/delete/{id}', [AttributeFamilyController::class, 'destroy'])->name('admin.catalog.families.delete');
    });
});
