<?php

use Illuminate\Support\Facades\Route;
use Webkul\Admin\Http\Controllers\NotificationController;

/**
 * Notification routes.
 */
Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
    Route::get('', 'index')->name('admin.notification.index');

    Route::get('list', 'list')->name('admin.notification.list');

    Route::post('read-all', 'readAll')->name('admin.notification.read_all');

    Route::get('{id}/open', 'open')->name('admin.notification.open');

    Route::post('{id}/read', 'markRead')->name('admin.notification.mark_read');

    Route::delete('{id}/read', 'markUnread')->name('admin.notification.mark_unread');
});

/**
 * Notification routes a panel written before notifications were typed still calls.
 *
 * @deprecated Use the routes above.
 */
Route::controller(NotificationController::class)->group(function () {
    Route::get('get-notifications', 'getNotifications')->name('admin.notification.get_notification');

    Route::get('viewed-notifications/{orderId}', 'viewedNotifications')->name('admin.notification.viewed_notification');

    Route::post('read-all-notifications', 'readAllNotifications')->name('admin.notification.read_all_notifications');
});
