<?php

namespace Webkul\Admin\SystemHealth\Checks;

class ApplicationUrlCheck extends AbstractCheck
{
    /**
     * The translation key segment naming what this check looks after.
     */
    public function name(): string
    {
        return 'application-url';
    }

    /**
     * Whether APP_URL disagrees with the address the request arrived on: plain HTTP on a secure
     * store, or a host that is neither the one requested nor any channel's.
     */
    public function failing(): bool
    {
        if (! $this->applies()) {
            return false;
        }

        $configured = parse_url((string) config('app.url'));

        if (
            request()->isSecure()
            && ($configured['scheme'] ?? null) === 'http'
        ) {
            return true;
        }

        $host = strtolower($configured['host'] ?? '');

        return $host !== strtolower(request()->getHost())
            && ! in_array($host, $this->channelHosts(), true);
    }

    /**
     * Whether there is a routed request to compare APP_URL against.
     */
    public function applies(): bool
    {
        return ! is_null(request()->route());
    }

    /**
     * The configured address and the host the store was reached on.
     */
    public function context(): array
    {
        return [
            'url' => (string) config('app.url'),
            'host' => request()->getHost(),
        ];
    }

    /**
     * The hosts the store's channels are served on, without scheme or path.
     *
     * @return list<string>
     */
    protected function channelHosts(): array
    {
        return core()->getAllChannels()
            ->pluck('hostname')
            ->filter()
            ->map(fn (string $hostname) => strtolower(parse_url($hostname, PHP_URL_HOST) ?: explode('/', $hostname)[0]))
            ->values()
            ->all();
    }
}
