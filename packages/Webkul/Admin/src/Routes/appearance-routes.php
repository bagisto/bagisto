<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Appearance\SectionController;
use Webkul\Admin\Http\Controllers\Appearance\ThemeController;

Route::prefix('appearance')->group(function () {
    /**
     * Theme gallery routes.
     */
    Route::controller(ThemeController::class)->prefix('themes')->group(function () {
        Route::get('', 'index')->name('admin.appearance.themes.index');

        Route::get('{code}/impact', 'impact')->name('admin.appearance.themes.impact');

        Route::post('{code}/activate', 'activate')->name('admin.appearance.themes.activate');
    });

    /**
     * Section routes.
     */
    Route::controller(SectionController::class)->prefix('sections')->group(function () {
        Route::get('', 'index')->name('admin.appearance.sections.index');

        Route::get('edit/{id}', 'edit')->name('admin.appearance.sections.edit');

        Route::post('store', 'store')->name('admin.appearance.sections.store');

        Route::post('edit/{id}', 'update')->name('admin.appearance.sections.update');

        Route::delete('edit/{id}', 'destroy')->name('admin.appearance.sections.delete');

        Route::post('mass-update', 'massUpdate')->name('admin.appearance.sections.mass_update');

        Route::post('mass-delete', 'massDestroy')->name('admin.appearance.sections.mass_delete');
    });
});

/**
 * Legacy theme routes. The screen moved from Settings to Appearance and its rows are
 * now called sections, these are kept so that bookmarks, documentation and existing
 * integrations keep working.
 */
Route::prefix('themes')->group(function () {
    Route::get('', fn () => redirect()->route('admin.appearance.sections.index', request()->query(), 301))
        ->name('admin.settings.themes.index');

    Route::get('edit/{id}', fn ($id) => redirect()->route('admin.appearance.sections.edit', ['id' => $id] + request()->query(), 301))
        ->name('admin.settings.themes.edit');

    Route::post('store', fn () => redirect()->route('admin.appearance.sections.store', [], 308))
        ->name('admin.settings.themes.store');

    Route::post('edit/{id}', fn ($id) => redirect()->route('admin.appearance.sections.update', ['id' => $id], 308))
        ->name('admin.settings.themes.update');

    Route::delete('edit/{id}', fn ($id) => redirect()->route('admin.appearance.sections.delete', ['id' => $id], 308))
        ->name('admin.settings.themes.delete');

    Route::post('mass-update', fn () => redirect()->route('admin.appearance.sections.mass_update', [], 308))
        ->name('admin.settings.themes.mass_update');

    Route::post('mass-delete', fn () => redirect()->route('admin.appearance.sections.mass_delete', [], 308))
        ->name('admin.settings.themes.mass_delete');
});
