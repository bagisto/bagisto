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
