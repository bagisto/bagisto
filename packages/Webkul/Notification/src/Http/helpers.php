<?php

use Webkul\Notification\Notifier;

if (! function_exists('notifier')) {
    /**
     * Notifier helper, raising a notification for a panel's users.
     */
    function notifier(): Notifier
    {
        return app(Notifier::class);
    }
}
