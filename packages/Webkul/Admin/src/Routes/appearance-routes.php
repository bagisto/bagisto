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
     * Section routes. A section belongs to a theme, so the listing and the create
     * endpoint hang off the theme; everything else is addressed by section id.
     */
    Route::controller(SectionController::class)->group(function () {
        Route::get('themes/{code}/sections', 'index')->name('admin.appearance.sections.index');

        Route::post('themes/{code}/sections', 'store')->name('admin.appearance.sections.store');

        Route::post('themes/{code}/sections/publish', 'publish')->name('admin.appearance.sections.publish');

        Route::post('themes/{code}/sections/discard', 'discard')->name('admin.appearance.sections.discard');

        Route::prefix('sections')->group(function () {
            Route::post('edit/{id}', 'update')->name('admin.appearance.sections.update');

            Route::delete('edit/{id}', 'destroy')->name('admin.appearance.sections.delete');

            Route::post('reorder', 'reorder')->name('admin.appearance.sections.reorder');

            Route::get('{id}/fields', 'fields')->name('admin.appearance.sections.fields');

            Route::post('{id}/draft', 'saveDraft')->name('admin.appearance.sections.draft');

            Route::post('{id}/duplicate', 'duplicate')->name('admin.appearance.sections.duplicate');

            Route::post('{id}/media', 'uploadMedia')->name('admin.appearance.sections.media');

            Route::post('{id}/status', 'status')->name('admin.appearance.sections.status');
        });
    });
});
