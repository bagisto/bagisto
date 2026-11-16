<?php

namespace Webkul\Admin\Notifications\Types;

class AppUrlMismatch extends SystemProblem
{
    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'app-url-mismatch';
    }
}
