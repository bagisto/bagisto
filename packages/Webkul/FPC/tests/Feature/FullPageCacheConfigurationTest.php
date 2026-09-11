<?php

use Webkul\FPC\CacheProfiles\FullPageCacheProfile;

/**
 * One section of the admin's system configuration, by key.
 */
function configSection(string $key): ?array
{
    foreach (config('core') as $section) {
        if (($section['key'] ?? null) === $key) {
            return $section;
        }
    }

    return null;
}

/**
 * One field of a system configuration section, by name.
 */
function configField(string $sectionKey, string $name): ?array
{
    foreach (configSection($sectionKey)['fields'] ?? [] as $field) {
        if (($field['name'] ?? null) === $name) {
            return $field;
        }
    }

    return null;
}

it('drives the page cache through the profile that reads the admin settings', function () {
    // Act & Assert
    expect(config('responsecache.cache_profile'))->toBe(FullPageCacheProfile::class);
});

it('offers Full Page Cache under Cache Management, after General', function () {
    // Act
    $section = configSection('cache_management.full_page_cache');

    $general = configSection('cache_management.general');

    // Assert
    expect($section)->not->toBeNull();

    expect($section['sort'])->toBeGreaterThan($general['sort']);
});

it('gives the Full Page Cache section an icon of its own', function () {
    // Arrange
    $icon = configSection('cache_management.full_page_cache')['icon'];

    // Act
    $path = base_path('packages/Webkul/Admin/src/Resources/assets/images/'.$icon);

    // Assert
    expect($icon)->not->toBe(configSection('cache_management.general')['icon'] ?? null);

    expect(file_exists($path))->toBeTrue("The configured icon {$icon} does not exist.");
});

it('turns the page cache on by default so an upgrade does not silently disable it', function () {
    // Act
    $field = configField('cache_management.full_page_cache.settings', 'enabled');

    // Assert
    expect($field['type'])->toBe('boolean');

    expect($field['default'])->toBeTrue();
});

it('accepts only a positive number of minutes as the cache lifetime', function () {
    // Act
    $field = configField('cache_management.full_page_cache.settings', 'lifetime');

    // Assert
    expect($field['validation'])->toBe('nullable|numeric|min:1');
});

it('keeps the settings store wide, not per channel or per locale', function (string $name) {
    // Act
    $field = configField('cache_management.full_page_cache.settings', $name);

    // Assert
    expect($field['channel_based'])->toBeFalse();

    expect($field['locale_based'])->toBeFalse();
})->with(['enabled', 'lifetime']);

it('translates every Full Page Cache string in every admin locale', function (string $locale) {
    // Arrange
    $keys = [
        'admin::app.configuration.index.cache-management.full-page-cache.title',
        'admin::app.configuration.index.cache-management.full-page-cache.info',
        'admin::app.configuration.index.cache-management.full-page-cache.settings.title',
        'admin::app.configuration.index.cache-management.full-page-cache.settings.info',
        'admin::app.configuration.index.cache-management.full-page-cache.settings.enabled',
        'admin::app.configuration.index.cache-management.full-page-cache.settings.enabled-info',
        'admin::app.configuration.index.cache-management.full-page-cache.settings.lifetime',
        'admin::app.configuration.index.cache-management.full-page-cache.settings.lifetime-info',
    ];

    // Act & Assert
    foreach ($keys as $key) {
        expect(trans($key, [], $locale))->not->toBe($key, "{$key} is missing from the {$locale} locale.");
    }
})->with(fn () => array_map(
    'basename',
    glob(dirname(__DIR__, 3).'/Admin/src/Resources/lang/*', GLOB_ONLYDIR)
));
