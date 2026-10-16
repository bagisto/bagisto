<?php

use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Point the application at a remote disk, as choosing S3 or R2 in the admin does.
 */
function useRemoteDisk(): void
{
    config([
        'filesystems.default' => 's3',
        'filesystems.disks.s3' => [
            'driver' => 's3',
            'key' => 'key',
            'secret' => 'secret',
            'region' => 'us-east-1',
            'bucket' => 'bagisto',
            'endpoint' => 'http://storage.invalid:9000',
            'use_path_style_endpoint' => true,
            'throw' => false,
        ],
    ]);
}

// ============================================================================
// Configuration Pages On A Remote Disk
// ============================================================================

it('should open a configuration section whose settings have never been saved', function () {
    $this->loginAsAdmin();

    useRemoteDisk();

    get(route('admin.configuration.index', ['slug' => 'file_management', 'slug2' => 'cloudflare_r2']))
        ->assertOk();
});

it('should open every file management section while a remote disk is chosen', function (string $section) {
    $this->loginAsAdmin();

    useRemoteDisk();

    get(route('admin.configuration.index', ['slug' => 'file_management', 'slug2' => $section]))
        ->assertOk();
})->with(['general', 'amazon_s3', 'cloudflare_r2']);

it('should not ask a remote disk for the url of a setting that holds nothing', function () {
    useRemoteDisk();

    expect(fn () => Storage::disk('s3')->url(null))->toThrow(TypeError::class);
});

// ============================================================================
// Offering The Sync Only Where It Applies
// ============================================================================

it('should offer the sync button when the chosen driver can be copied onto', function (string $disk) {
    $this->loginAsAdmin();

    config(['filesystems.default' => $disk]);

    get(route('admin.configuration.index', ['slug' => 'file_management', 'slug2' => 'general']))
        ->assertOk()
        ->assertSee('v-storage-sync', false);
})->with(['s3', 'r2']);

it('should tie the sync button to the driver on screen, so it goes as soon as one is picked that cannot be synced onto', function () {
    $this->loginAsAdmin();

    config(['filesystems.default' => 's3']);

    get(route('admin.configuration.index', ['slug' => 'file_management', 'slug2' => 'general']))
        ->assertOk()
        ->assertSee('depend-name="file_management[general][settings][default_driver]"', false)
        ->assertSee('depend-value="s3,r2"', false);
});

it('should leave the sync button out while the files are served from the site itself', function () {
    $this->loginAsAdmin();

    config(['filesystems.default' => 'public']);

    get(route('admin.configuration.index', ['slug' => 'file_management', 'slug2' => 'general']))
        ->assertOk()
        ->assertDontSee('v-storage-sync', false);
});

it('should save the chosen driver whichever one it is, now that the sync button depends on it', function (string $driver) {
    $this->loginAsAdmin();

    postJson(route('admin.configuration.store', ['file_management', 'general']), [
        'locale' => core()->getDefaultLocaleCodeFromDefaultChannel(),
        'channel' => core()->getDefaultChannel()->code,
        'file_management' => [
            'general' => [
                'settings' => [
                    'default_driver' => $driver,
                ],
            ],
        ],
    ])->assertRedirect();

    $this->assertDatabaseHas('core_config', [
        'code' => 'file_management.general.settings.default_driver',
        'value' => $driver,
    ]);
})->with(['public', 's3', 'r2']);

// ============================================================================
// Who May Reach The Sync
// ============================================================================

it('should let a role granted configuration access reach the sync endpoints', function () {
    $this->loginAsAdminWithPermissions(['configuration']);

    config(['filesystems.default' => 'public']);

    get(route('admin.configuration.file_management.sync_status'))->assertOk();

    postJson(route('admin.configuration.file_management.sync'))->assertUnprocessable();
});

it('should refuse the sync endpoints to a role without configuration access', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.configuration.file_management.sync_status'))->assertUnauthorized();

    postJson(route('admin.configuration.file_management.sync'))->assertUnauthorized();
});

// ============================================================================
// The Storage Sync Endpoint
// ============================================================================

it('should report the count of files the configured disk is missing', function () {
    $this->loginAsAdmin();

    Storage::fake('public');

    Storage::fake('s3');

    config(['filesystems.default' => 's3']);

    Storage::disk('public')->put('products/1/front.webp', 'front');

    get(route('admin.configuration.file_management.sync_status'))
        ->assertOk()
        ->assertJson(['pending' => 1, 'local' => false, 'unreachable' => false]);
});

it('should answer that there is nothing to copy when the local disk is the configured one', function () {
    $this->loginAsAdmin();

    config(['filesystems.default' => 'public']);

    get(route('admin.configuration.file_management.sync_status'))
        ->assertOk()
        ->assertJson(['pending' => 0, 'local' => true]);
});

it('should say the disk is unreachable rather than failing when it cannot be listed', function () {
    $this->loginAsAdmin();

    useRemoteDisk();

    get(route('admin.configuration.file_management.sync_status'))
        ->assertOk()
        ->assertJson(['unreachable' => true]);
});
