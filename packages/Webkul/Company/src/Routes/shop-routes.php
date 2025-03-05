<?php

use Illuminate\Support\Facades\Route;
use Webkul\Company\Http\Controllers\Shop\CompanyController;

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency'], 'prefix' => 'company'], function () {
    Route::get('', [CompanyController::class, 'index'])->name('shop.company.index');
    Route::get('detail/{id}', [CompanyController::class, 'detail'])->name('shop.company.detail');
});
