<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::get('/search', 'Webkul\Velocity\Http\Controllers\Shop\ShopController@search')->defaults('_config', [
        'view' => 'shop::search.search'
    ])->name('velocity.search.index');
});