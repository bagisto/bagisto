<?php

use Illuminate\Support\Facades\Route;
use Brainstream\Giftcard\Http\Controllers\Admin\GiftCardController;

Route::group(['middleware' => ['web', 'admin'], 'prefix' => 'admin/giftcard'], function () {
    Route::controller(GiftCardController::class)->group(function () {
        Route::get('', 'index')->name('admin.giftcard.index');

        Route::post('create', 'store')->name('admin.giftcard.store');

        Route::get('edit/{id}', 'edit')->name('admin.giftcard.edit'); 

        Route::put('edit', 'update')->name('admin.giftcard.update'); 
        
        Route::delete('edit/{id}', 'destroy')->name('admin.giftcard.delete');

        Route::get('payment', 'payments')->name('admin.giftcard.payment');

        Route::get('balance', 'balances')->name('admin.giftcard.balance');
    });
});