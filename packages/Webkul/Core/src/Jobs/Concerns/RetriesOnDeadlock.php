<?php

namespace Webkul\Core\Jobs\Concerns;

use Illuminate\Database\DetectsConcurrencyErrors;
use Illuminate\Database\QueryException;

trait RetriesOnDeadlock
{
    use DetectsConcurrencyErrors;

    /**
     * Run the callback again while the database reports a lock conflict, waiting longer each
     * time so competing transactions stop retrying in lockstep. The work must be re-runnable.
     */
    protected function retryOnDeadlock(callable $callback, int $attempts = 5)
    {
        for ($attempt = 1; ; $attempt++) {
            try {
                return $callback();
            } catch (QueryException $e) {
                if (
                    ! $this->causedByConcurrencyError($e)
                    || $attempt >= $attempts
                ) {
                    throw $e;
                }

                usleep($attempt * 100000);
            }
        }
    }
}
