<?php

namespace Webkul\Admin\SystemHealth\Checks;

abstract class AbstractCheck
{
    /**
     * The translation key segment naming what this check looks after.
     */
    abstract public function name(): string;

    /**
     * Whether the problem this check looks for is present.
     */
    abstract public function failing(): bool;

    /**
     * Whether the store uses what this check looks after. One that does not apply never fails.
     */
    public function applies(): bool
    {
        return true;
    }

    /**
     * Whether the check cannot yet tell: what it listens for has not been heard from even once.
     */
    public function unconfirmed(): bool
    {
        return false;
    }

    /**
     * Details kept with the notification raised for the problem.
     */
    public function context(): array
    {
        return [];
    }
}
