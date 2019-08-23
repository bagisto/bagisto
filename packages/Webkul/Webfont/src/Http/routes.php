<?php

Route::group(['middleware' => ['admin']], function () {
    Route::get('admin/webfont', 'Webkul\Webfont\Http\Controllers\WebfontController@index')->name('admin.cms.webfont');

    Route::get('admin/webfont/add', 'Webkul\Webfont\Http\Controllers\WebfontController@add')->name('admin.cms.webfont.add');

    Route::post('admin/webfont/add', 'Webkul\Webfont\Http\Controllers\WebfontController@store')->name('admin.cms.webfont.store');

    Route::get('admin/webfont/activate/{id}', 'Webkul\Webfont\Http\Controllers\WebfontController@activate')->name('admin.cms.webfont.activate');

    Route::post('admin/webfont/remove/{id}', 'Webkul\Webfont\Http\Controllers\WebfontController@remove')->name('admin.cms.webfont.remove');
});
