<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {

    Route::prefix('customer')->group(function () {

        Route::group(['middleware' => ['customer']], function () {

            Route::prefix('account')->group(function () {

                Route::get('documents', 'Webkul\CustomerDocument\Http\Controllers\DocumentController@index')->defaults('_config', [
                    'view' => 'customerdocument::shop.customers.document'
                ])->name('customer.documents.index');

                Route::get('download-document/{id}', 'Webkul\CustomerDocument\Http\Controllers\DocumentController@download')->defaults('_config', [
                    'redirect' => 'admin.customer.index'
                ])->name('customer.document.download');
            });
        });
    });
});