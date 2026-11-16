<?php

namespace Webkul\Admin\Notifications\Types;

class QueueWorkerStopped extends SystemProblem
{
    /**
     * The translation key segment holding this problem's title and message.
     */
    protected function name(): string
    {
        return 'queue-worker-stopped';
    }
}
