<?php

use Illuminate\Support\Facades\Route;
use Webkul\DataGrid\Http\Controllers\FilterController;

Route::controller(FilterController::class)->group(function () {
    Route::post('datagrid-filters', 'store')->name('datagrid.filters.store');
    Route::get('datagrid-filters', 'get')->name('datagrid.filters.index');
});

