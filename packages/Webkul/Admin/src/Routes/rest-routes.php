<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\DashboardController;
use Webkul\Admin\Http\Controllers\TinyMCEController;
use Webkul\Admin\Http\Controllers\User\AccountController;
use Webkul\Admin\Http\Controllers\User\SessionController;

/**
 * Extra routes.
 */
Route::group(['middleware' => ['admin'], 'prefix' => config('app.admin_url')], function () {
    /**
     * Dashboard routes.
     */
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

    /**
     * Tinymce file upload handler.
     */
    Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])->name('admin.tinymce.upload');

    /**
     * Admin profile routes.
     */
    Route::controller(AccountController::class)->prefix('account')->group(function () {
        Route::get('', 'edit')->name('admin.account.edit');

        Route::put('', 'update')->name('admin.account.update');
    });

    Route::delete('logout', [SessionController::class, 'destroy'])->name('admin.session.destroy');
});
