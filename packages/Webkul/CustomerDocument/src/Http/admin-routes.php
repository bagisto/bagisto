<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //document Management Routes
            Route::get('documents', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@index')->defaults('_config',[
                'view' => 'customerdocument::admin.documents.index'
            ])->name('admin.documents.index');

            Route::get('documents/create', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@create')->defaults('_config',[
                'view' => 'customerdocument::admin.documents.create'
            ])->name('admin.documents.create');

            Route::post('documents/create', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@store')->defaults('_config',[
                'redirect' => 'admin.documents.index'
            ])->name('admin.documents.store');

            Route::get('documents/edit/{id}', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@edit')->defaults('_config',[
                'view' => 'customerdocument::admin.documents.edit'
            ])->name('admin.documents.edit');

            Route::put('documents/edit/{id}', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@update')->defaults('_config',[
                'redirect' => 'admin.documents.index'
            ])->name('admin.documents.update');

            Route::post('documents/delete/{id}', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@destroy')->name('admin.documents.delete');

            Route::get('download-document/{id}', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@download')->defaults('_config', [
                'view' => 'customerdocument::admin.documents.edit'
            ])->name('admin.documents.download');

            Route::get('customer-documents/delete/{id}', 'Webkul\CustomerDocument\Http\Controllers\Admin\DocumentController@delete')->name('admin.customer.documents.delete');
        });

    });

});
