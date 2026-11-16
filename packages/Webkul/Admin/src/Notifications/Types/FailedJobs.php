<?php

namespace Webkul\Admin\Notifications\Types;

class FailedJobs extends SystemProblem
{
    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'failed-jobs';
    }
}
