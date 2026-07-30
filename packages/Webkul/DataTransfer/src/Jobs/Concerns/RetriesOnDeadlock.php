<?php

namespace Webkul\DataTransfer\Jobs\Concerns;

use Illuminate\Database\QueryException;

/**
 * Retries a write that InnoDB rolled back to break a lock cycle — import batches
 * run in parallel and write to the same handful of tables, so one can be chosen
 * as the victim through no fault of its own.
 *
 * Only deadlock and lock-wait-timeout errors are retried, with a short growing
 * pause; every other query failure is re-thrown untouched.
 */
trait RetriesOnDeadlock
{
    /**
     * Run the callback, retrying it while MySQL reports a deadlock or a lock-wait
     * timeout. Re-throws once the attempts are spent.
     */
    protected function retryOnDeadlock(callable $callback, int $attempts = 5)
    {
        for ($attempt = 1; ; $attempt++) {
            try {
                return $callback();
            } catch (QueryException $e) {
                if (
                    ! $this->isDeadlock($e)
                    || $attempt >= $attempts
                ) {
                    throw $e;
                }

                /**
                 * Back off a little further each time so the competing
                 * transactions stop retrying in lockstep.
                 */
                usleep($attempt * 100000);
            }
        }
    }

    /**
     * Is this query failure a deadlock or a lock-wait timeout?
     */
    protected function isDeadlock(QueryException $e): bool
    {
        $code = (int) ($e->errorInfo[1] ?? 0);

        return in_array($code, [1213, 1205], true);
    }
}
