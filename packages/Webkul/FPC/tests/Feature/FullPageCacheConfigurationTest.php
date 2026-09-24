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

// ============================================================================
// Cache Profile
// ============================================================================

it('should drive the page cache through the profile that reads the admin settings', function () {
    expect(config('responsecache.cache_profile'))->toBe(FullPageCacheProfile::class);
});

// ============================================================================
// Configuration Section
// ============================================================================

it('should offer Full Page Cache under Cache Management, after General', function () {
    $section = configSection('cache_management.full_page_cache');

    $general = configSection('cache_management.general');

    expect($section)->not->toBeNull()
        ->and($section['sort'])->toBeGreaterThan($general['sort']);
});

it('should give the Full Page Cache section an icon of its own', function () {
    $icon = configSection('cache_management.full_page_cache')['icon'];

    $path = base_path('packages/Webkul/Admin/src/Resources/assets/images/'.$icon);

    expect($icon)->not->toBe(configSection('cache_management.general')['icon'] ?? null)
        ->and(file_exists($path))->toBeTrue("The configured icon {$icon} does not exist.");
});

// ============================================================================
// Configuration Fields
// ============================================================================

it('should turn the page cache on by default so an upgrade does not silently disable it', function () {
    $field = configField('cache_management.full_page_cache.settings', 'enabled');

    expect($field['type'])->toBe('boolean')
        ->and($field['default'])->toBe(1);
});

it('should accept only a positive number of minutes as the cache lifetime', function () {
    $field = configField('cache_management.full_page_cache.settings', 'lifetime');

    expect($field['validation'])->toBe('nullable|numeric|min:1');
});

it('should keep the settings store wide, not per channel or per locale', function (string $name) {
    $field = configField('cache_management.full_page_cache.settings', $name);

    expect($field['channel_based'])->toBeFalse()
        ->and($field['locale_based'])->toBeFalse();
})->with(['enabled', 'lifetime']);

// ============================================================================
// Translations
// ============================================================================

it('should translate every Full Page Cache string in every admin locale', function (string $locale) {
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

    foreach ($keys as $key) {
        expect(trans($key, [], $locale))->not->toBe($key, "{$key} is missing from the {$locale} locale.");
    }
})->with(fn () => array_map(
    'basename',
    glob(dirname(__DIR__, 3).'/Admin/src/Resources/lang/*', GLOB_ONLYDIR)
));
