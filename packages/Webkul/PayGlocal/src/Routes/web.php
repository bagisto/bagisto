<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Webkul\PayGlocal\Http\Controllers\PayGlocalController;

Route::controller(PayGlocalController::class)
    ->middleware('web')
    ->prefix('payglocal')
    ->group(function () {
        Route::get('redirect', 'redirect')->name('payglocal.redirect');

        /**
         * PayGlocal posts the callback cross site, so the browser sends no cookies with it.
         * Letting the session middleware run would start a brand new session and hand it
         * back through "Set-Cookie", signing the customer out of the storefront. All three
         * exclusions are needed: "ShareErrorsFromSession" and "VerifyCsrfToken" both reach
         * for the session on their own, even on a CSRF exempt route.
         */
        Route::post('callback', 'callback')
            ->withoutMiddleware([
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
            ])
            ->name('payglocal.callback');

        Route::get('success', 'success')->name('payglocal.success');

        Route::post('webhook', 'webhook')
            ->withoutMiddleware(VerifyCsrfToken::class)
            ->name('payglocal.webhook');
    });
