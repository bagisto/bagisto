<?php

namespace Webkul\Admin\SystemHealth\Checks;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class BroadcastingCheck extends AbstractCheck
{
    /**
     * Seconds to wait for the broadcasting server before calling it unreachable.
     */
    protected const TIMEOUT = 2;

    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'broadcasting';
    }

    /**
     * Whether Reverb is the broadcaster and nothing answers at its address. Any reply counts as reachable.
     */
    public function failing(): bool
    {
        if (! $this->applies()) {
            return false;
        }

        $options = config('broadcasting.connections.reverb.options', []);

        try {
            Http::timeout(self::TIMEOUT)
                ->connectTimeout(self::TIMEOUT)
                ->get(($options['scheme'] ?? 'https').'://'.$options['host'].':'.($options['port'] ?? 443));
        } catch (ConnectionException) {
            return true;
        }

        return false;
    }

    /**
     * Whether Reverb is the broadcaster and has an address to reach it on.
     */
    public function applies(): bool
    {
        return config('broadcasting.default') === 'reverb'
            && ! empty(config('broadcasting.connections.reverb.options.host'));
    }
}
