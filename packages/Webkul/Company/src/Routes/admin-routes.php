<?php

use Illuminate\Support\Facades\Route;
use Webkul\Company\Http\Controllers\Admin\CompanyController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/company'], function () {
    Route::controller(CompanyController::class)->group(function () {
        Route::get('', 'index')->name('admin.company.index');
        Route::get('detail/{id}', 'detail')->name('admin.company.detail');
    });
});
