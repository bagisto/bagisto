<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            // Bulk Upload Products
            Route::get('bulkupload-upload-files', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@index')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.upload-files.index'
            ])->name('admin.bulk-upload.index');

            Route::get('bulk-upload-run-profile', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@index')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.run-profile.index'
            ])->name('admin.run-profile.index');

            Route::post('read-csv', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@readCSVData')
            ->name('bulk-upload-admin.read-csv');

            Route::post('getprofiles', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@getAllDataFlowProfiles')
            ->name('bulk-upload-admin.get-all-profile');


            // Download Sample Files
            Route::post('download','Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@downloadFile')->defaults('_config',[
                'view' => 'bulkupload::admin.bulk-upload.upload-files.index'
            ])->name('download-sample-files');

            // import new products
            Route::post('importnew', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@importNewProductsStore')->defaults('_config',['view' => 'bulkupload::admin.bulk-upload.upload-files.index' ])->name('import-new-products-form-submit');

            Route::get('dataflowprofile', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@index')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.data-flow-profile.index'
            ])->name('admin.dataflow-profile.index');

            Route::post('addprofile', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@store')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.data-flow-profile.index'
            ])->name('bulkupload.bulk-upload.dataflow.add-profile');

            Route::post('runprofile', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@runProfile')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.run-profile.progressbar'
            ])->name('bulk-upload-admin.run-profile');

            // edit actions
            Route::get('/dataflowprofile/delete/{id}','Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@destroy')->name('bulkupload.admin.profile.delete');

            Route::get('/dataflowprofile/edit/{id}', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@edit')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.data-flow-profile.edit'
            ])->name('bulkupload.admin.profile.edit');

            Route::post('/dataflowprofile/update/{id}', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@update')->defaults('_config', [
                'view' => 'bulkupload::admin.bulk-upload.data-flow-profile.index'
            ])->name('admin.bulk-upload.dataflow.update-profile');

            //mass destroy

            Route::post('products/massdestroy', 'Webkul\Bulkupload\Http\Controllers\Admin\BulkUploadController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.dataflow-profile.index'
            ])->name('bulkupload.admin.profile.massDelete');
        });
    });
});