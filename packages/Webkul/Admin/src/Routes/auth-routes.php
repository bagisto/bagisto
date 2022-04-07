<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Http\Controllers\ForgetPasswordController;
use Webkul\User\Http\Controllers\ResetPasswordController;
use Webkul\User\Http\Controllers\SessionController;

/**
 * Auth routes.
 */
Route::group(['middleware' => ['web'], 'prefix' => config('app.admin_url')], function () {
    /**
     * Redirect route.
     */
    Route::get('/', [Controller::class, 'redirectToLogin']);

    /**
     * Login routes.
     */
    Route::get('/login', [SessionController::class, 'create'])->defaults('_config', [
        'view' => 'admin::users.sessions.create',
    ])->name('admin.session.create');

    /**
     * Login post route to admin auth controller.
     */
    Route::post('/login', [SessionController::class, 'store'])->defaults('_config', [
        'redirect' => 'admin.dashboard.index',
    ])->name('admin.session.store');

    /**
     * Forget password routes.
     */
    Route::get('/forget-password', [ForgetPasswordController::class, 'create'])->defaults('_config', [
        'view' => 'admin::users.forget-password.create',
    ])->name('admin.forget-password.create');

    Route::post('/forget-password', [ForgetPasswordController::class, 'store'])->name('admin.forget-password.store');

    /**
     * Reset password routes.
     */
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->defaults('_config', [
        'view' => 'admin::users.reset-password.create',
    ])->name('admin.reset-password.create');

    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->defaults('_config', [
        'redirect' => 'admin.dashboard.index',
    ])->name('admin.reset-password.store');
});
