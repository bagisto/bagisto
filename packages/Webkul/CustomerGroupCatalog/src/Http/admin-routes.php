<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //Seller routes
            Route::get('groups/catalog/search', 'Webkul\CustomerGroupCatalog\Http\Controllers\Admin\CustomerGroupController@search')->name('admin.customer_group_catalog.search.catalog');

            Route::post('groups/create', 'Webkul\CustomerGroupCatalog\Http\Controllers\Admin\CustomerGroupController@store')->defaults('_config',[
                'redirect' => 'admin.groups.index'
            ])->name('admin.customer_group_catalog.store');

            Route::put('groups/edit/{id}', 'Webkul\CustomerGroupCatalog\Http\Controllers\Admin\CustomerGroupController@update')->defaults('_config',[
                'redirect' => 'admin.groups.index'
            ])->name('admin.customer_group_catalog.update');
        });

    });

});