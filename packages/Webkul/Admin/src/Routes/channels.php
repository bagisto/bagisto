<?php

use Illuminate\Support\Facades\Broadcast;

/**
 * Admin notifications reach the panel over this channel, so only a signed in admin may listen —
 * the same audience the notification endpoints already serve.
 */
Broadcast::channel('admin.notifications', function ($admin) {
    return (bool) $admin;
}, ['guards' => ['admin']]);
