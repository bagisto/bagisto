<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Customer\AddressController;
use Webkul\Admin\Http\Controllers\Customer\CustomerController;
use Webkul\Admin\Http\Controllers\Customer\CustomerGroupController;
use Webkul\Admin\Http\Controllers\Product\ReviewController;

/**
 * Customers routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    /**
     * Customer's addresses routes.
     */
    Route::controller(AddressController::class)->prefix('customers')->group(function () {
        Route::prefix('{id}/addresses')->group(function () {
            Route::get('', 'index')->name('admin.customer.addresses.index');

            Route::get('create', 'create')->name('admin.customer.addresses.create');

            Route::post('create', 'store')->name('admin.customer.addresses.store');

            Route::post('', 'massDestroy')->name('admin.customer.addresses.mass_delete');
        });

        Route::prefix('addresses')->group(function () {
            Route::get('edit/{id}', 'edit')->name('admin.customer.addresses.edit');

            Route::put('edit/{id}', 'update')->name('admin.customer.addresses.update');

            Route::post('delete/{id}', 'destroy')->name('admin.customer.addresses.delete');
        });
    });

    /**
     * Customer management routes.
     */
    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('', 'index')->name('admin.customer.index');

        Route::post('create', 'store')->name('admin.customer.store');

        Route::get('edit/{id}', 'edit')->name('admin.customer.edit');

        Route::get('loginascustomer/{id}', 'loginAsCustomer')->name('admin.customer.loginascustomer');

        Route::get('note/{id}', 'createNote')->name('admin.customer.note.create');

        Route::put('note/{id}', 'storeNote')->name('admin.customer.note.store');

        Route::put('edit/{id}', 'update')->name('admin.customer.update');

        Route::post('delete/{id}', 'destroy')->name('admin.customer.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.customer.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('admin.customer.mass_update');

        Route::get('{id}/invoices', 'invoices')->name('admin.customer.invoices.data');

        Route::get('{id}/orders', 'orders')->name('admin.customer.orders.data');
    });

    /**
     * Customer groups routes.
     */
    Route::controller(CustomerGroupController::class)->prefix('groups')->group(function () {
        Route::get('', 'index')->name('admin.groups.index');

        Route::post('create', 'store')->name('admin.groups.store');

        Route::get('edit/{id}', 'edit')->name('admin.groups.edit');

        Route::put('edit/{id}', 'update')->name('admin.groups.update');

        Route::post('delete/{id}', 'destroy')->name('admin.groups.delete');
    });

    /**
     * Customer's reviews routes.
     */
    Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
        Route::get('', 'index')->name('admin.customer.review.index');

        Route::get('edit/{id}', 'edit')->name('admin.customer.review.edit');

        Route::put('edit/{id}', 'update')->name('admin.customer.review.update');

        Route::post('delete/{id}', 'destroy')->name('admin.customer.review.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.customer.review.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('admin.customer.review.mass_update');
    });
});
