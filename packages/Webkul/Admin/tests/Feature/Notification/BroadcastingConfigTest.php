<?php

use Webkul\Admin\Helpers\Broadcasting;

// ============================================================================
// Disabled Broadcasting
// ============================================================================

it('should expose no client configuration for a connection a browser cannot reach', function ($connection) {
    config(['broadcasting.default' => $connection]);

    expect(app(Broadcasting::class)->clientConfig())->toBeNull();
})->with([null, 'null', 'log', 'redis', 'ably']);

it('should expose no client configuration when the connection has no key', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => null,
        'broadcasting.connections.reverb.options.host' => 'localhost',
    ]);

    expect(app(Broadcasting::class)->clientConfig())->toBeNull();
});

it('should expose no client configuration when the connection has no host', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => 'reverb-key',
        'broadcasting.connections.reverb.options.host' => null,
    ]);

    expect(app(Broadcasting::class)->clientConfig())->toBeNull();
});

// ============================================================================
// Client Configuration
// ============================================================================

it('should expose the reverb connection to the browser', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => 'reverb-key',
        'broadcasting.connections.reverb.options.host' => 'localhost',
        'broadcasting.connections.reverb.options.port' => '8080',
        'broadcasting.connections.reverb.options.scheme' => 'http',
    ]);

    expect(app(Broadcasting::class)->clientConfig())->toBe([
        'connection' => 'reverb',
        'key' => 'reverb-key',
        'host' => 'localhost',
        'port' => 8080,
        'forceTLS' => false,
        'cluster' => null,
    ]);
});

it('should expose the pusher connection to the browser', function () {
    config([
        'broadcasting.default' => 'pusher',
        'broadcasting.connections.pusher.key' => 'pusher-key',
        'broadcasting.connections.pusher.options.host' => 'api-mt1.pusher.com',
        'broadcasting.connections.pusher.options.port' => 443,
        'broadcasting.connections.pusher.options.scheme' => 'https',
        'broadcasting.connections.pusher.options.cluster' => 'mt1',
    ]);

    expect(app(Broadcasting::class)->clientConfig())->toBe([
        'connection' => 'pusher',
        'key' => 'pusher-key',
        'host' => 'api-mt1.pusher.com',
        'port' => 443,
        'forceTLS' => true,
        'cluster' => 'mt1',
    ]);
});

// ============================================================================
// Secret Handling
// ============================================================================

it('should never expose the application secret or id to the browser', function () {
    config([
        'broadcasting.default' => 'reverb',
        'broadcasting.connections.reverb.key' => 'reverb-key',
        'broadcasting.connections.reverb.secret' => 'reverb-secret',
        'broadcasting.connections.reverb.app_id' => 'reverb-id',
        'broadcasting.connections.reverb.options.host' => 'localhost',
    ]);

    expect(app(Broadcasting::class)->clientConfig())
        ->not->toContain('reverb-secret')
        ->not->toContain('reverb-id');
});
