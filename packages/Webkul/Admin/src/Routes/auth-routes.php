<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Controllers\User\ForgetPasswordController;
use Webkul\Admin\Http\Controllers\User\ResetPasswordController;
use Webkul\Admin\Http\Controllers\User\SessionController;
use Webkul\Admin\Http\Controllers\User\TwoFactorController;

/**
 * Auth routes.
 */
Route::group(['prefix' => config('app.admin_url')], function () {
    /**
     * Redirect route.
     */
    Route::get('/', [Controller::class, 'redirectToLogin']);

    Route::controller(SessionController::class)->prefix('login')->group(function () {
        /**
         * Login routes.
         */
        Route::get('', 'create')->name('admin.session.create');

        /**
         * Login post route to admin auth controller.
         */
        Route::post('', 'store')->name('admin.session.store');
    });

    /**
     * Forget password routes.
     */
    Route::controller(ForgetPasswordController::class)->prefix('forget-password')->group(function () {
        Route::get('', 'create')->name('admin.forget_password.create');

        Route::post('', 'store')->name('admin.forget_password.store');
    });

    /**
     * Reset password routes.
     */
    Route::controller(ResetPasswordController::class)->prefix('reset-password')->group(function () {
        Route::get('{token}', 'create')->name('admin.reset_password.create');

        Route::post('', 'store')->name('admin.reset_password.store');
    });
});

Route::group(['middleware' => ['web', 'admin', 'admin.2fa']], function () {

    Route::group(['prefix' => 'admin/two-factor'], function () {
        Route::get('/verify', [TwoFactorController::class, 'showVerifyForm'])->name('admin.twofactor.verify.form');

        Route::post('/verify', [TwoFactorController::class, 'verifyTwoFactorCode'])->name('admin.twofactor.verifyTwoFactorCode');
    });

    Route::get('/admin/logout', [SessionController::class, 'destroy'])->name('admin.twofactor.session.destroy');
});
