<?php

namespace Webkul\Admin\Helpers;

class Broadcasting
{
    /**
     * Broadcast connections a browser is able to open a websocket against.
     */
    public const CLIENT_CONNECTIONS = ['reverb', 'pusher'];

    /**
     * Public broadcasting settings for the admin javascript client, or null when there are none.
     */
    public function clientConfig(): ?array
    {
        $connection = config('broadcasting.default');

        if (! in_array($connection, self::CLIENT_CONNECTIONS, true)) {
            return null;
        }

        $key = config('broadcasting.connections.'.$connection.'.key');

        $options = config('broadcasting.connections.'.$connection.'.options', []);

        if (
            empty($key)
            || empty($options['host'])
        ) {
            return null;
        }

        return [
            'connection' => $connection,
            'key' => $key,
            'host' => $options['host'],
            'port' => (int) ($options['port'] ?? 443),
            'forceTLS' => ($options['scheme'] ?? 'https') === 'https',
            'cluster' => $options['cluster'] ?? null,
        ];
    }
}
