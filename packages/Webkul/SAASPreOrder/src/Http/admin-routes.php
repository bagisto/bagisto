<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            //Seller routes
            Route::get('preorders', 'Webkul\SAASPreOrder\Http\Controllers\Admin\PreOrderController@index')->defaults('_config', [
                'view' => 'preorder::admin.preorders.index'
            ])->name('admin.preorder.preorders.index');

            //product massdelete
            Route::post('preorders/notify-customer', 'Webkul\SAASPreOrder\Http\Controllers\Admin\PreOrderController@notifyCustomer')->defaults('_config', [
                'redirect' => 'admin.preorder.preorders.index'
            ])->name('admin.preorder.preorders.notify-customer');
        });

    });

});