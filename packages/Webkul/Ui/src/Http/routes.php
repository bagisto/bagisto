<?php

Route::view('/ui-kit', 'ui::ui-kit');

Route::get('/users', function () {
    $users = \Webkul\User\Models\Admin::paginate(1);
    echo $users->links();
});