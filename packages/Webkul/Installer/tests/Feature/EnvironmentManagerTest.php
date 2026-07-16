<?php

use Webkul\Installer\Helpers\EnvironmentManager;

/**
 * Invoke the protected `resolveEnvVariable` parser directly so the parsing can be
 * exercised without touching the real `.env` file (which is shared across parallel
 * test processes).
 */
function resolveEnv(array $lines, string $key, $default = null)
{
    $method = new ReflectionMethod(EnvironmentManager::class, 'resolveEnvVariable');

    $method->setAccessible(true);

    return $method->invoke(new EnvironmentManager, $lines, $key, $default);
}

it('should preserve a value that contains an equals sign', function () {
    $lines = [
        'DB_PASSWORD="examplePassword="',
    ];

    expect(resolveEnv($lines, 'DB_PASSWORD'))->toBe('examplePassword=');
});

it('should preserve a value that contains multiple equals signs', function () {
    $lines = [
        'TOKEN=abc=def=ghi',
    ];

    expect(resolveEnv($lines, 'TOKEN'))->toBe('abc=def=ghi');
});

it('should preserve spaces inside a quoted value', function () {
    $lines = [
        'APP_NAME="My Store"',
    ];

    expect(resolveEnv($lines, 'APP_NAME'))->toBe('My Store');
});

it('should trim whitespace around the key and the equals sign', function () {
    $lines = [
        'DB_HOST = 127.0.0.1',
    ];

    expect(resolveEnv($lines, 'DB_HOST'))->toBe('127.0.0.1');
});

it('should return an empty string for a key with no value', function () {
    $lines = [
        'DB_PASSWORD=',
    ];

    expect(resolveEnv($lines, 'DB_PASSWORD'))->toBe('');
});

it('should skip blank lines and comments', function () {
    $lines = [
        '# DB_PASSWORD=commented',
        '',
        'DB_PASSWORD=realSecret',
    ];

    expect(resolveEnv($lines, 'DB_PASSWORD'))->toBe('realSecret');
});

it('should match the key exactly and not by substring', function () {
    $lines = [
        'APP_ENV=production',
        'APP_ENV_LABEL=staging',
    ];

    expect(resolveEnv($lines, 'APP_ENV_LABEL'))->toBe('staging');
});

it('should return the default when the key is not present', function () {
    $lines = [
        'APP_ENV=production',
    ];

    expect(resolveEnv($lines, 'DB_PASSWORD', 'fallback'))->toBe('fallback');
});
