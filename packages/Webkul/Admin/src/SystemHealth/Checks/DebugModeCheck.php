<?php

namespace Webkul\Admin\SystemHealth\Checks;

class DebugModeCheck extends AbstractCheck
{
    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'debug-mode';
    }

    /**
     * Whether a production store shows visitors its error details.
     */
    public function failing(): bool
    {
        return app()->isProduction()
            && (bool) config('app.debug');
    }
}
