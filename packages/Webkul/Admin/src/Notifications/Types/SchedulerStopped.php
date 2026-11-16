<?php

namespace Webkul\Admin\Notifications\Types;

class SchedulerStopped extends SystemProblem
{
    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'scheduler-stopped';
    }
}
