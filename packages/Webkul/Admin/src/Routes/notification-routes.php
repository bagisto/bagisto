<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\User\Http\Controllers\ForgetPasswordController;
use Webkul\User\Http\Controllers\ResetPasswordController;
use Webkul\User\Http\Controllers\SessionController;

/**
 * Auth routes.
 */
Route::group(['middleware' => ['web', 'admin_locale'], 'prefix' => config('app.admin_url')], function () {
    // notification
    Route::get('notifications', 'Webkul\Notification\Http\Controllers\Admin\NotificationController@index')->defaults('_config', [
        'view' => 'admin::notifications.index',
    ])->name('admin.notification.index');

    // get notification
    Route::get('get-notifications', 'Webkul\Notification\Http\Controllers\Admin\NotificationController@getNotifications')
        ->name('admin.notification.get-notification');

    //view order  
    Route::get('viewed-notifications/{orderId}', 'Webkul\Notification\Http\Controllers\Admin\NotificationController@viewedNotifications')
        ->name('admin.notification.viewed-notification');

    // read all notification
    Route::post('read-all-notifications', 'Webkul\Notification\Http\Controllers\Admin\NotificationController@readAllNotifications')
        ->name('admin.notification.read-all');
});
