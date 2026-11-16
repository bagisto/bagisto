<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Illuminate\Queue\Failed\CountableFailedJobProvider;

class FailedJobsCheck extends AbstractCheck
{
    /**
     * How many failed jobs were found the last time this check ran.
     */
    protected int $count = 0;

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'failed-jobs';
    }

    /**
     * Whether any queued job has failed and not been retried or cleared.
     */
    public function failing(): bool
    {
        $failer = app('queue.failer');

        if (! $failer instanceof CountableFailedJobProvider) {
            return false;
        }

        $this->count = $failer->count();

        return $this->count > 0;
    }

    /**
     * How many jobs had failed when the problem was found.
     */
    public function context(): array
    {
        return ['count' => $this->count];
    }
}
