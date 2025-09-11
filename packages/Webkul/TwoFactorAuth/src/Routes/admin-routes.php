<?php

use Illuminate\Support\Facades\Route;
use Webkul\TwoFactorAuth\Http\Controllers\Admin\TwoFactorController;

Route::group(['middleware' => ['web', 'admin', 'admin.2fa']], function () {
    Route::get('/admin/two-factor/setup', [TwoFactorController::class, 'setup'])->name('admin.twofactor.setup');

    Route::post('/admin/two-factor/enable', [TwoFactorController::class, 'enable'])->name('admin.twofactor.enable');

    Route::get('/admin/two-factor/verify', [TwoFactorController::class, 'showVerifyForm'])->name('admin.twofactor.verify.form');

    Route::post('/admin/two-factor/verify', [TwoFactorController::class, 'verify'])->name('admin.twofactor.verify');
});