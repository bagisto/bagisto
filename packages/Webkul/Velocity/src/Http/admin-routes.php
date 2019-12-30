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

                // Main Category Route
                Route::get('/category', 'CategoryController@index')->defaults('_config', [
                    'view' => 'velocity::admin.category.index'
                ])->name('velocity.admin.category.index');

                Route::get('/category/create', 'CategoryController@create')->defaults('_config',[
                    'view' => 'velocity::admin.category.create'
                ])->name('velocity.admin.category.create');

                Route::post('/category/create', 'CategoryController@store')->defaults('_config',[
                    'redirect' => 'velocity.admin.category.index'
                ])->name('velocity.admin.category.store');

                Route::get('/category/edit/{id}', 'CategoryController@edit')->defaults('_config',[
                    'view' => 'velocity::admin.category.edit'
                ])->name('velocity.admin.category.edit');

                Route::put('/category/edit/{id}', 'CategoryController@update')->defaults('_config', [
                    'redirect' => 'velocity.admin.category.index'
                ])->name('velocity.admin.category.update');

                Route::post('/category/delete/{id}', 'CategoryController@destroy')->name('velocity.admin.category.delete');

                Route::post('/category/masssdelete', 'CategoryController@massDestroy')->defaults('_config', [
                    'redirect' => 'velocity.admin.category.index'
                ])->name('velocity.admin.category.mass-delete');

                Route::get('/meta-data', 'ConfigurationController@storeMetaData')->defaults('_config', [
                    'view' => 'velocity::admin.meta-info.meta-data'
                ])->name('velocity.admin.meta-data');
            });
        });
    });

    Route::group(['middleware' => ['theme']], function () {
        Route::get('/search', 'Webkul\Velocity\Http\Controllers\Shop\SearchController@index')->defaults('_config', [
            'view' => 'velocity::search.search'
        ])->name('shop.search.index');
    });
});