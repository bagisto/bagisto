<?php

use Illuminate\Support\Facades\Route;
use Webkul\TwoFactorAuth\Http\Controllers\Admin\TwoFactorAuthController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/twofactorauth'], function () {
    Route::controller(TwoFactorAuthController::class)->group(function () {
        Route::get('', 'index')->name('admin.twofactorauth.index');
    });
});