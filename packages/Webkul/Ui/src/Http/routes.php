<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

if (App::environment(['local', 'staging', 'development'])) {
    Route::view('/ui-kit', 'ui::partials.ui-kit');

    Route::view('/helper-classess', 'ui::partials.helper-classes')->name('ui.helper.classes');
}
