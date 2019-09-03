<?php

Route::group(['middleware' => ['web', 'theme', 'locale', 'currency']], function () {
    Route::get('/complete-preorder/{token}', 'Webkul\PreOrder\Http\Controllers\Shop\PreOrderController@complete')->name('preorder.shop.preorder.complete');
});