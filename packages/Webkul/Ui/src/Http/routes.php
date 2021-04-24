<?php

Route::view('/ui-kit', 'ui::partials.ui-kit');

Route::view('/helper-classess', 'ui::partials.helper-classes')->name('ui.helper.classes');

Route::get('test1', function () {
    return app('Webkul\Admin\DataGrids\ProductDataGrid')->toJson();
})->name('ui.test1');