<?php

use Webkul\Core\Enums\SupportedFilesystemEnum;

// ============================================================================
// The Driver In Force
// ============================================================================

it('should read the driver from the filesystem configuration', function (string $disk, SupportedFilesystemEnum $driver) {
    config(['filesystems.default' => $disk]);

    expect(SupportedFilesystemEnum::currentDriver())->toBe($driver);
})->with([
    'the local disk' => ['public', SupportedFilesystemEnum::LOCAL],
    'Amazon S3' => ['s3', SupportedFilesystemEnum::S3],
    'Cloudflare R2' => ['r2', SupportedFilesystemEnum::R2],
]);

it('should fall back to the local driver when the configured disk is not one it manages', function () {
    config(['filesystems.default' => 'ftp']);

    expect(SupportedFilesystemEnum::currentDriver())->toBe(SupportedFilesystemEnum::LOCAL);
});

// ============================================================================
// Which Drivers Can Be Synced Onto
// ============================================================================

it('should offer to copy existing files onto a remote driver', function (SupportedFilesystemEnum $driver) {
    expect($driver->supportsSync())->toBeTrue();
})->with([
    'Amazon S3' => [SupportedFilesystemEnum::S3],
    'Cloudflare R2' => [SupportedFilesystemEnum::R2],
]);

it('should not offer to copy existing files onto the disk they are already on', function () {
    expect(SupportedFilesystemEnum::LOCAL->supportsSync())->toBeFalse();
});

it('should list the drivers worth syncing onto, as the configuration screen records them', function () {
    expect(SupportedFilesystemEnum::syncable())->toBe(['s3', 'r2']);
});

// ============================================================================
// What A Driver Needs Before It Can Be Reached
// ============================================================================

it('should name the settings a remote driver must carry before it is selected', function (SupportedFilesystemEnum $driver, array $settings) {
    expect($driver->requiredDiskSettings())->toBe($settings);
})->with([
    'Amazon S3' => [SupportedFilesystemEnum::S3, ['key', 'secret', 'region', 'bucket']],
    'Cloudflare R2' => [SupportedFilesystemEnum::R2, ['key', 'secret', 'bucket', 'endpoint']],
]);

it('should ask nothing of the local disk, which is always reachable', function () {
    expect(SupportedFilesystemEnum::LOCAL->requiredDiskSettings())->toBe([]);
});
