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
    'amazon s3' => ['s3', SupportedFilesystemEnum::S3],
    'cloudflare r2' => ['r2', SupportedFilesystemEnum::R2],
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
    'amazon s3' => [SupportedFilesystemEnum::S3],
    'cloudflare r2' => [SupportedFilesystemEnum::R2],
]);

it('should not offer to copy existing files onto the disk they are already on', function () {
    expect(SupportedFilesystemEnum::LOCAL->supportsSync())->toBeFalse();
});

it('should list the drivers worth syncing onto, as the configuration screen records them', function () {
    expect(SupportedFilesystemEnum::syncable())->toBe(['s3', 'r2']);
});
