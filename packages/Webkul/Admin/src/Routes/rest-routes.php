<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\DashboardController;
use Webkul\Admin\Http\Controllers\ExportController;
use Webkul\Admin\Http\Controllers\TinyMCEController;
use Webkul\User\Http\Controllers\AccountController;
use Webkul\User\Http\Controllers\SessionController;

/**
 * Extra routes.
 */
Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    /**
     * Tinymce file upload handler.
     */
    Route::post('tinymce/upload', [TinyMCEController::class, 'upload'])
        ->name('admin.tinymce.upload');

    /**
     * Dashboard routes.
     */
    Route::get('dashboard', [DashboardController::class, 'index'])->defaults('_config', [
        'view' => 'admin::dashboard.index',
    ])->name('admin.dashboard.index');

    /**
     * Admin profile routes.
     */
    Route::get('/account', [AccountController::class, 'edit'])->defaults('_config', [
        'view' => 'admin::account.edit',
    ])->name('admin.account.edit');

    Route::put('/account', [AccountController::class, 'update'])->name('admin.account.update');

    Route::get('/logout', [SessionController::class, 'destroy'])->defaults('_config', [
        'redirect' => 'admin.session.create',
    ])->name('admin.session.destroy');

    /**
     * DataGrid export.
     */
    Route::post('/export', [ExportController::class, 'export'])->name('admin.datagrid.export');
});
