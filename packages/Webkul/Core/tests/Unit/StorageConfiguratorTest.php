<?php

use Illuminate\Support\Facades\DB;
use Webkul\Core\Filesystem\StorageConfigurator;

/**
 * Record the file management settings the admin would have saved, keyed by their config code.
 */
function recordFileManagement(array $settings): void
{
    foreach ($settings as $code => $value) {
        DB::table('core_config')->insert([
            'code' => $code,
            'value' => $value,
        ]);
    }
}

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

    recordFileManagement([
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

    recordFileManagement([
        'file_management.general.settings.default_driver' => 's3',
        'file_management.amazon_s3.settings.key' => '',
        'file_management.amazon_s3.settings.secret' => '',
        'file_management.amazon_s3.settings.region' => '',
        'file_management.amazon_s3.settings.bucket' => '',
    ]);

    app(StorageConfigurator::class)->configure();

    expect(config('filesystems.default'))->toBe('public');
});

it('should keep serving from the site when r2 is chosen without a bucket', function () {
    config(['filesystems.default' => 'public']);

    config(['filesystems.disks.r2' => ['driver' => 's3', 'throw' => false]]);

    recordFileManagement([
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

    recordFileManagement([
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
