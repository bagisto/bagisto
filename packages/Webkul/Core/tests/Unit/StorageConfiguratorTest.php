<?php

use Webkul\Core\Filesystem\StorageConfigurator;

/**
 * Point the s3 disk at the settings the environment would otherwise supply.
 */
function presetS3Disk(array $settings): void
{
    config(['filesystems.disks.s3' => array_merge([
        'driver' => 's3',
        'throw' => false,
    ], $settings)]);
}

// ============================================================================
// Switching The Default Disk
// ============================================================================

it('should switch the default disk once every setting the chosen driver needs is present', function () {
    config(['filesystems.default' => 'public']);

    $this->setConfig([
        'file_management.general.settings.default_driver' => 's3',
        'file_management.amazon_s3.settings.key' => 'key',
        'file_management.amazon_s3.settings.secret' => 'secret',
        'file_management.amazon_s3.settings.region' => 'us-east-1',
        'file_management.amazon_s3.settings.bucket' => 'bagisto',
    ]);

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('s3')
        ->and(config('filesystems.disks.s3.bucket'))->toBe('bagisto');
});

it('should keep serving from the site when a remote disk is chosen without the credentials it needs', function () {
    config(['filesystems.default' => 'public']);

    presetS3Disk([
        'key' => null,
        'secret' => null,
        'region' => null,
        'bucket' => null,
    ]);

    $this->setConfig([
        'file_management.general.settings.default_driver' => 's3',
        'file_management.amazon_s3.settings.key' => '',
        'file_management.amazon_s3.settings.secret' => '',
        'file_management.amazon_s3.settings.region' => '',
        'file_management.amazon_s3.settings.bucket' => '',
    ]);

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('public');
});

it('should keep serving from the site when R2 is chosen without a bucket', function () {
    config(['filesystems.default' => 'public']);

    config(['filesystems.disks.r2' => ['driver' => 's3', 'throw' => false]]);

    $this->setConfig([
        'file_management.general.settings.default_driver' => 'r2',
        'file_management.cloudflare_r2.settings.key' => 'key',
        'file_management.cloudflare_r2.settings.secret' => 'secret',
        'file_management.cloudflare_r2.settings.account_id' => 'account',
        'file_management.cloudflare_r2.settings.bucket' => '',
    ]);

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('public');
});

it('should switch to a remote disk the environment has configured even when the admin left the form blank', function () {
    config(['filesystems.default' => 'public']);

    presetS3Disk([
        'key' => 'env-key',
        'secret' => 'env-secret',
        'region' => 'us-east-1',
        'bucket' => 'env-bucket',
    ]);

    $this->setConfig([
        'file_management.general.settings.default_driver' => 's3',
        'file_management.amazon_s3.settings.key' => '',
        'file_management.amazon_s3.settings.secret' => '',
        'file_management.amazon_s3.settings.region' => '',
        'file_management.amazon_s3.settings.bucket' => '',
    ]);

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('s3')
        ->and(config('filesystems.disks.s3.bucket'))->toBe('env-bucket');
});

it('should keep serving from the site when S3 has a bucket but no access keys', function () {
    config(['filesystems.default' => 'public']);

    presetS3Disk([
        'key' => null,
        'secret' => null,
        'region' => 'us-east-1',
        'bucket' => 'bucket',
    ]);

    $this->setConfig('file_management.general.settings.default_driver', 's3');

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('public');
});

// ============================================================================
// The Disk The Environment Names
// ============================================================================

it('should leave a remote disk the environment names when it is missing settings and nothing is chosen', function () {
    config(['filesystems.default' => 's3']);

    presetS3Disk([
        'key' => 'env-key',
        'secret' => 'env-secret',
        'region' => 'us-east-1',
        'bucket' => null,
    ]);

    $this->setConfig('file_management.general.settings.default_driver', '');

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('public');
});

it('should keep a complete remote disk the environment names when nothing is chosen', function () {
    config(['filesystems.default' => 's3']);

    presetS3Disk([
        'key' => 'env-key',
        'secret' => 'env-secret',
        'region' => 'us-east-1',
        'bucket' => 'env-bucket',
    ]);

    $this->setConfig('file_management.general.settings.default_driver', '');

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('s3');
});

it('should leave an incomplete remote disk the environment names when the chosen one is incomplete too', function () {
    config(['filesystems.default' => 's3']);

    presetS3Disk([
        'key' => null,
        'secret' => null,
        'region' => 'us-east-1',
        'bucket' => null,
    ]);

    config(['filesystems.disks.r2' => ['driver' => 's3', 'throw' => false]]);

    $this->setConfig('file_management.general.settings.default_driver', 'r2');

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('public');
});

it('should leave a disk the store does not manage as the environment set it', function () {
    config(['filesystems.default' => 'local']);

    $this->setConfig('file_management.general.settings.default_driver', '');

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('local');
});
