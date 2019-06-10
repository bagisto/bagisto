<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //document Management Routes
            Route::post('upload-document', 'Webkul\CustomerDocument\Http\Controllers\DocumentController@upload')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.document.upload');

            Route::get('download-document/{id}', 'Webkul\CustomerDocument\Http\Controllers\DocumentController@download')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.document.download');

            Route::get('delete-document/{id}', 'Webkul\CustomerDocument\Http\Controllers\DocumentController@delete')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.document.delete');

        });

    });

});
