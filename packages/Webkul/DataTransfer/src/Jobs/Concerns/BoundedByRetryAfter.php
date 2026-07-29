<?php

namespace Webkul\DataTransfer\Jobs\Concerns;

/**
 * Keeps a job's timeout under the queue connection's `retry_after`.
 *
 * A worker holds its job for `retry_after` seconds; past that the queue treats it
 * as abandoned and hands the same payload to another worker while the first is
 * still running it. A job allowed to outlast that window therefore executes
 * twice, concurrently, duplicating its work and decrementing its batch twice.
 *
 * Deriving the timeout rather than hard-coding it means the two can never be
 * configured into conflict.
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
