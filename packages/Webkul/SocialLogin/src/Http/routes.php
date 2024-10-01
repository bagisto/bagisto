<?php

use Illuminate\Support\Facades\Route;
use Webkul\SocialLogin\Http\Controllers\LoginController;

Route::controller(LoginController::class)->prefix('customer/social-login/{provider}')->group(function () {
    Route::get('', 'redirectToProvider')->name('customer.social-login.index');

    Route::get('callback', 'handleProviderCallback')->name('customer.social-login.callback');
});
