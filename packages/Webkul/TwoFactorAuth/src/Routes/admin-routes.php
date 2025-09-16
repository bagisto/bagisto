<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\User\SessionController;
use Webkul\TwoFactorAuth\Http\Controllers\Admin\TwoFactorController;

Route::group(['middleware' => ['web', 'admin', 'admin.2fa']], function () {

    Route::group(['prefix' => 'admin/two-factor'], function () {

        Route::get('/setup', [TwoFactorController::class, 'setup'])->name('admin.twofactor.setup');

        Route::post('/enable', [TwoFactorController::class, 'enable'])->name('admin.twofactor.enable');

        Route::get('/remove', [TwoFactorController::class, 'remove'])->name('admin.twofactor.configuration.remove');

        Route::get('/verify', [TwoFactorController::class, 'showVerifyForm'])->name('admin.twofactor.verify.form');

        Route::post('/verify', [TwoFactorController::class, 'verifyTwoFactorCode'])->name('admin.twofactor.verifyTwoFactorCode');
    });

    Route::get('/admin/logout', [SessionController::class, 'destroy'])->name('admin.twofactor.session.destroy');

});
