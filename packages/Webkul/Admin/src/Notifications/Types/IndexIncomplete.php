<?php

namespace Webkul\Admin\Notifications\Types;

class IndexIncomplete extends SystemProblem
{
    /**
     * The icon class shown beside the notification.
     */
    protected string $icon = 'icon-product';

    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'index-incomplete';
    }
}
