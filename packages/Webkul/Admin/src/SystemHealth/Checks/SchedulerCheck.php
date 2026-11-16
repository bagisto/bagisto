<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Webkul\Admin\SystemHealth\Heartbeat;

class SchedulerCheck extends AbstractCheck
{
    /**
     * The heartbeat the scheduler keeps.
     */
    public const HEARTBEAT = 'scheduler';

    /**
     * Seconds the scheduler may stay silent. It beats every minute.
     */
    public const SILENCE_LIMIT = 300;

    /**
     * Create a new check instance.
     */
    public function __construct(protected Heartbeat $heartbeat) {}

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'scheduler';
    }

    /**
     * Whether the scheduler has not run for longer than its limit.
     */
    public function failing(): bool
    {
        return $this->heartbeat->silence(self::HEARTBEAT) > self::SILENCE_LIMIT;
    }

    /**
     * Whether it is expected to run but has not been heard from even once.
     */
    public function unconfirmed(): bool
    {
        return $this->applies()
            && ! $this->heartbeat->heard(self::HEARTBEAT);
    }
}
