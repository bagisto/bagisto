<?php

use Illuminate\Support\Facades\Route;
use Webkul\PagoMovil\Http\Controllers\PagoMovilController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    Route::prefix('pagomovil')->group(function () {
        Route::get('/redirect', [PagoMovilController::class, 'redirect'])->name('pagomovil.redirect');
        Route::post('/store', [PagoMovilController::class, 'store'])->name('pagomovil.store');
    });
});
