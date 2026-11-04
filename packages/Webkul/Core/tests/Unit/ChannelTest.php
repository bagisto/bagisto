<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;

/**
 * Point the default disk at an s3 bucket that was never configured.
 */
function useBucketlessRemoteDisk(): void
{
    config([
        'filesystems.default' => 's3',
        'filesystems.disks.s3' => ['driver' => 's3', 'throw' => false],
    ]);

    Storage::forgetDisk('s3');
}

beforeEach(function () {
    $this->channelLocale = Locale::factory()->create();

    $this->channel = Channel::factory()->create();

    $this->channel->locales()->sync([$this->channelLocale->id]);

    $this->channel->default_locale_id = $this->channelLocale->id;

    $this->channel->save();
});

// ============================================================================
// Locale Resolution
// ============================================================================

it('should keep a locale code the channel has', function () {
    expect($this->channel->resolveLocaleCode($this->channelLocale->code))->toBe($this->channelLocale->code);
});

it('should resolve a locale code the channel lacks to the channel default locale', function () {
    $otherLocale = Locale::factory()->create();

    expect($this->channel->resolveLocaleCode($otherLocale->code))->toBe($this->channelLocale->code);
});

it('should resolve a missing locale code to the channel default locale', function () {
    expect($this->channel->resolveLocaleCode(null))->toBe($this->channelLocale->code);
});

// ============================================================================
// Media Urls On A Disk That Cannot Build Them
// ============================================================================

it('should give no channel logo or favicon url rather than fail when the disk cannot build one', function () {
    useBucketlessRemoteDisk();

    $this->channel->logo = 'channels/1/logo.png';

    $this->channel->favicon = 'channels/1/favicon.png';

    expect($this->channel->logo_url())->toBeNull()
        ->and($this->channel->favicon_url())->toBeNull();
});

it('should give no locale logo url rather than fail when the disk cannot build one', function () {
    useBucketlessRemoteDisk();

    $this->channelLocale->logo_path = 'locales/logo.png';

    expect($this->channelLocale->logo_url())->toBeNull();
});
