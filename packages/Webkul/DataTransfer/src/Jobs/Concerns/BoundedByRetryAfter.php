<?php

namespace Webkul\DataTransfer\Jobs\Concerns;

/**
 * Keeps a job's timeout under the queue connection's `retry_after`.
 *
 * Past `retry_after` the queue treats a job as abandoned and hands the payload to
 * a second worker while the first still runs it, so anything outlasting that
 * window executes twice. Deriving the timeout keeps the two in step.
 */
trait BoundedByRetryAfter
{
    /**
     * The longest this job may run, leaving a margin for the worker to finish up
     * and release the job before the queue would reclaim it.
     */
    protected static function timeoutWithinRetryAfter(int $margin = 30): int
    {
        $connection = config('queue.default');

        $retryAfter = (int) config("queue.connections.{$connection}.retry_after", 90);

        return max(30, $retryAfter - $margin);
    }
}
