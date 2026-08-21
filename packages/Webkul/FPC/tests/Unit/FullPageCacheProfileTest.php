<?php

use Illuminate\Http\Request;
use Webkul\Core\Facades\SystemConfig;
use Webkul\Core\Models\CoreConfig;
use Webkul\FPC\CacheProfiles\FullPageCacheProfile;

/**
 * Save a Full Page Cache setting the way the configuration screen does.
 */
function saveSetting(string $field, mixed $value): void
{
    CoreConfig::factory()->create([
        'code' => 'cache_management.full_page_cache.settings.'.$field,
        'value' => $value,
    ]);
}

beforeEach(function () {
    $this->profile = app(FullPageCacheProfile::class);

    $this->request = Request::create('/');

    config(['responsecache.enabled' => true]);
});

it('serves pages from the cache once the deployment switch and the admin setting are both on', function () {
    // Arrange
    saveSetting('enabled', '1');

    // Act & Assert
    expect($this->profile->enabled($this->request))->toBeTrue();
});

it('stops serving pages from the cache when the admin turns the setting off', function () {
    // Arrange
    saveSetting('enabled', '0');

    // Act & Assert
    expect($this->profile->enabled($this->request))->toBeFalse();
});

it('serves pages from the cache when the setting has never been saved', function () {
    // Act & Assert
    expect($this->profile->enabled($this->request))->toBeTrue();
});

it('keeps the deployment switch as the final word over the admin setting', function () {
    // Arrange
    config(['responsecache.enabled' => false]);

    saveSetting('enabled', '1');

    // Act & Assert
    expect($this->profile->enabled($this->request))->toBeFalse();
});

it('caches a page for the number of minutes configured in the admin panel', function () {
    // Arrange
    saveSetting('lifetime', '15');

    // Act
    $until = $this->profile->cacheRequestUntil($this->request);

    // Assert
    expect($until->getTimestamp())->toEqualWithDelta(now()->addMinutes(15)->getTimestamp(), 2);
});

it('falls back to the application lifetime when no admin lifetime is set', function () {
    // Arrange
    config(['responsecache.cache_lifetime_in_seconds' => 3600]);

    // Act
    $until = $this->profile->cacheRequestUntil($this->request);

    // Assert
    expect($until->getTimestamp())->toEqualWithDelta(now()->addSeconds(3600)->getTimestamp(), 2);
});

it('falls back to the application lifetime when the admin lifetime is cleared', function () {
    // Arrange
    config(['responsecache.cache_lifetime_in_seconds' => 3600]);

    saveSetting('lifetime', '');

    // Act
    $until = $this->profile->cacheRequestUntil($this->request);

    // Assert
    expect($until->getTimestamp())->toEqualWithDelta(now()->addSeconds(3600)->getTimestamp(), 2);
});

it('lets the request through when the settings store cannot be read', function () {
    // Arrange
    SystemConfig::shouldReceive('getConfigData')
        ->andThrow(new RuntimeException('the database is not usable yet'));

    // Act & Assert
    expect($this->profile->enabled($this->request))->toBeTrue();
});

it('keeps caching successful storefront GET requests', function () {
    // Act & Assert
    expect($this->profile->shouldCacheRequest($this->request))->toBeTrue();

    expect($this->profile->shouldCacheRequest(Request::create('/', 'POST')))->toBeFalse();
});
