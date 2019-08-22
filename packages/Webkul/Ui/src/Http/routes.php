<?php

Route::view('/ui-kit', 'ui::partials.ui-kit');

Route::view('/helper-classess', 'ui::partials.helper-classes')->name('ui.helper.classes');

// Route::get('/users', function () {
//     $users = \Webkul\User\Models\Admin::paginate(1);
//     echo $users->links();
// });