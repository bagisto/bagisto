<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Admin\SystemHealth\Heartbeat;

class QueueWorkerCheck extends AbstractCheck
{
    /**
     * The heartbeat a queue worker keeps.
     */
    public const HEARTBEAT = 'queue';

    /**
     * Seconds a worker may stay silent, long enough for one slow job.
     */
    public const SILENCE_LIMIT = 600;

    /**
     * The queue drivers that run jobs without a worker.
     */
    protected const WORKERLESS_DRIVERS = ['sync', 'null', 'deferred', 'background'];

    /**
     * Create a new check instance.
     */
    public function __construct(protected Heartbeat $heartbeat) {}

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'queue-worker';
    }

    /**
     * Whether a worker is needed and none has been heard from within the limit.
     */
    public function failing(): bool
    {
        return $this->applies()
            && $this->heartbeat->silence(self::HEARTBEAT) > self::SILENCE_LIMIT;
    }

    /**
     * Whether it is expected to run but has not been heard from even once.
     */
    public function unconfirmed(): bool
    {
        return $this->applies()
            && ! $this->heartbeat->heard(self::HEARTBEAT);
    }

    /**
     * Whether the default queue is one that needs a worker.
     */
    public function applies(): bool
    {
        $driver = config('queue.connections.'.config('queue.default').'.driver');

        return ! in_array($driver, self::WORKERLESS_DRIVERS, true);
    }

    /**
     * The queue connection left without a worker.
     */
    public function context(): array
    {
        return ['connection' => config('queue.default')];
    }
}
