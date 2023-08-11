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
     * Customer management routes.
     */
    Route::controller(CustomerController::class)->prefix('customers')->group(function () {
        Route::get('', 'index')->name('admin.customer.index');

        Route::get('view/{id}', 'show')->name('admin.customer.view');

        Route::post('create', 'store')->name('admin.customer.store');

        Route::get('edit/{id}', 'edit')->name('admin.customer.edit');

        Route::get('login-as-customer/{id}', 'login_as_customer')->name('admin.customer.login_as_customer');

        Route::post('note/{id}', 'storeNotes')->name('admin.customer.note.store');

        Route::post('edit/{id}', 'update')->name('admin.customer.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.customer.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.customer.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('admin.customer.mass_update');

        Route::get('{id}/invoices', 'invoices')->name('admin.customer.invoices.data');

        Route::get('{id}/orders', 'orders')->name('admin.customer.orders.data');
    });

    /**
     * Customer's addresses routes.
     */
    Route::controller(AddressController::class)->prefix('customers')->group(function () {
        Route::prefix('{id}/addresses')->group(function () {
            Route::get('', 'index')->name('admin.customer.addresses.index');

            Route::get('create', 'create')->name('admin.customer.addresses.create');

            Route::post('create', 'store')->name('admin.customer.addresses.store');

            Route::post('mass-delete', 'massDestroy')->name('admin.customer.addresses.mass_delete');
        });

        Route::prefix('addresses')->group(function () {
            Route::get('edit/{id}', 'edit')->name('admin.customer.addresses.edit');

            Route::put('edit/{id}', 'update')->name('admin.customer.addresses.update');

            Route::post('default/{id}', 'makeDefault')->name('admin.customer.addresses.set_default');

            Route::post('delete/{id}', 'destroy')->name('admin.customer.addresses.delete');
        });
    });

    /**
     * Customer's reviews routes.
     */
    Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
        Route::get('', 'index')->name('admin.customer.review.index');

        Route::get('edit/{id}', 'edit')->name('admin.customer.review.edit');

        Route::put('edit/{id}', 'update')->name('admin.customer.review.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.customer.review.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.customer.review.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('admin.customer.review.mass_update');
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
});
