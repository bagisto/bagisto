<?php

namespace Webkul\Admin\Notifications\Types;

class BroadcastUnreachable extends SystemProblem
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-notification';

    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'broadcast-unreachable';
    }
}
