<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Customer\AddressController;
use Webkul\Admin\Http\Controllers\Customer\CustomerController;
use Webkul\Admin\Http\Controllers\Customer\CustomerGroupController;
use Webkul\Admin\Http\Controllers\Customer\ReviewController;

/**
 * Customers routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url') . '/customers'], function () {
    Route::prefix('customers')->group(function () {
        /**
         * Customer management routes.
         */
        Route::controller(CustomerController::class)->group(function () {
            Route::get('', 'index')->name('admin.customers.customer.index');

            Route::get('view/{id}', 'show')->name('admin.customers.customer.view');

            Route::post('create', 'store')->name('admin.customers.customer.store');

            Route::get('edit/{id}', 'edit')->name('admin.customers.customer.edit');

            Route::get('search', 'search')->name('admin.customers.customer.search');

            Route::get('login-as-customer/{id}', 'login_as_customer')->name('admin.customers.customer.login_as_customer');

            Route::post('note/{id}', 'storeNotes')->name('admin.customer.note.store');

            Route::post('edit/{id}', 'update')->name('admin.customers.customer.update');

            Route::post('/{id}', 'destroy')->name('admin.customers.customer.delete');

            Route::post('mass-delete', 'massDestroy')->name('admin.customers.customer.mass_delete');

            Route::post('mass-update', 'massUpdate')->name('admin.customers.customer.mass_update');

            Route::get('{id}/orders', 'orders')->name('admin.customers.customer.orders.data');
        });

        /**
         * Customer's addresses routes.
         */
        Route::controller(AddressController::class)->group(function () {
            Route::prefix('{id}/addresses')->group(function () {
                Route::get('', 'index')->name('admin.customers.customer.addresses.index');

                Route::get('create', 'create')->name('admin.customers.customer.addresses.create');
                
                Route::post('create', 'store')->name('admin.customers.customer.addresses.store');
            });

            Route::prefix('addresses')->group(function () {
                Route::get('edit/{id}', 'edit')->name('admin.customers.customer.addresses.edit');

                Route::post('edit/{id}', 'update')->name('admin.customers.customer.addresses.update');

                Route::post('default/{id}', 'makeDefault')->name('admin.customers.customer.addresses.set_default');

                Route::post('delete/{id}', 'destroy')->name('admin.customers.customer.addresses.delete');
            });
        });
    });

    /**
     * Customer's reviews routes.
     */
    Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
        Route::get('', 'index')->name('admin.customers.customer.review.index');

        Route::get('edit/{id}', 'edit')->name('admin.customers.customer.review.edit');

        Route::post('edit/{id}', 'update')->name('admin.customers.customer.review.update');

        Route::delete('/{id}', 'destroy')->name('admin.customers.customer.review.delete');

        Route::post('mass-delete', 'massDestroy')->name('admin.customers.customer.review.mass_delete');

        Route::post('mass-update', 'massUpdate')->name('admin.customers.customer.review.mass_update');
    });

    /**
     * Customer groups routes.
     */
    Route::controller(CustomerGroupController::class)->prefix('groups')->group(function () {
        Route::get('', 'index')->name('admin.customers.groups.index');

        Route::post('create', 'store')->name('admin.customers.groups.store');

        Route::post('edit', 'update')->name('admin.customers.groups.update');

        Route::delete('delete/{id}', 'destroy')->name('admin.customers.groups.delete');
    });
});
