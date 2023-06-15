<?php

// Controllers
use Webkul\SocialLogin\Http\Controllers\LoginController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('customer')->group(function () {
        Route::get('social-login/{provider}', [LoginController::class, 'redirectToProvider'])->defaults('_config', [
            'redirect' => 'shop.customer.profile.index'
        ])->name('customer.social-login.index');

        Route::get('social-login/{provider}/callback',[LoginController::class, 'handleProviderCallback'])->defaults('_config', [
            'redirect' => 'shop.customer.profile.index'
        ])->name('customer.social-login.callback');
    });
});