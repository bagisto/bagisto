<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin/velocity')->group(function () {
        Route::group(['middleware' => ['admin']], function () {
            Route::namespace('Webkul\Velocity\Http\Controllers\Admin')->group(function () {
                // Content Pages Route
                Route::get('/content', 'ContentController@index')->defaults('_config', [
                    'view' => 'velocity::admin.content.index'
                ])->name('velocity.admin.content.index');

                Route::get('/content/search', 'ContentController@search')->name('velocity.admin.content.search');

                Route::get('/content/create', 'ContentController@create')->defaults('_config',[
                    'view' => 'velocity::admin.content.create'
                ])->name('velocity.admin.content.create');

                Route::post('/content/create', 'ContentController@store')->defaults('_config',[
                    'redirect' => 'velocity.admin.content.index'
                ])->name('velocity.admin.content.store');

                Route::get('/content/edit/{id}', 'ContentController@edit')->defaults('_config',[
                    'view' => 'velocity::admin.content.edit'
                ])->name('velocity.admin.content.edit');

                Route::put('/content/edit/{id}', 'ContentController@update')->defaults('_config', [
                    'redirect' => 'velocity.admin.content.index'
                ])->name('velocity.admin.content.update');

                Route::post('/content/delete/{id}', 'ContentController@destroy')->name('velocity.admin.content.delete');

                Route::post('/content/masssdelete', 'ContentController@massDestroy')->defaults('_config', [
                    'redirect' => 'velocity.admin.content.index'
                ])->name('velocity.admin.content.mass-delete');

                Route::get('/meta-data', 'ConfigurationController@renderMetaData')->defaults('_config', [
                    'view' => 'velocity::admin.meta-info.meta-data'
                ])->name('velocity.admin.meta-data');

                Route::post('/meta-data/{id}', 'ConfigurationController@storeMetaData')->defaults('_config', [
                    'redirect' => 'velocity.admin.meta-data'
                ])->name('velocity.admin.store.meta-data');
            });
        });
    });
});