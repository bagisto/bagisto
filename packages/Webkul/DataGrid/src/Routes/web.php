<?php

use Illuminate\Support\Facades\Route;
use Webkul\DataGrid\Http\Controllers\FilterController;

Route::middleware(['web'])->group(function () {
    Route::controller(FilterController::class)->prefix('datagrid/saved-filters')->group(function () {
        Route::post('', 'store')->name('datagrid.filters.saved_filters.store');
        Route::get('', 'get')->name('datagrid.filters.saved_filters.index');
        Route::delete('{id}', 'destroy')->name('datagrid.filters.saved_filters.destroy');
    });
});
