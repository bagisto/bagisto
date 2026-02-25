<?php


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'es'])) {
        $locale = 'en';
    }

    Session::put('locale', $locale); // store in session
    return redirect()->back(); // go back to previous page
});