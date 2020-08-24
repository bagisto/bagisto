<?php

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('customer')->group(function () {
        Route::get('social-login/{provider}', 'Webkul\SocialLogin\Http\Controllers\LoginController@redirectToProvider')->defaults('_config', [
            'redirect' => 'customer.profile.index'
        ])->name('customer.social-login.index');

        Route::get('social-login/{provider}/callback','Webkul\SocialLogin\Http\Controllers\LoginController@handleProviderCallback')->defaults('_config', [
            'redirect' => 'customer.profile.index'
        ])->name('customer.social-login.callback');
    });
});