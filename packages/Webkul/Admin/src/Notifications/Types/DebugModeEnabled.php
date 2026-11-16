<?php

namespace Webkul\Admin\Notifications\Types;

class DebugModeEnabled extends SystemProblem
{
    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'debug-mode-enabled';
    }
}
