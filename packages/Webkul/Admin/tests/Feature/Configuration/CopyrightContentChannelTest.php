<?php

use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CoreConfigRepository;

/**
 * The configuration field holding the storefront footer copyright notice.
 */
function copyrightContentField(): string
{
    return 'general.content.footer.copyright_content';
}

/**
 * Store the copyright notice for one channel and locale, past the repository's cache.
 */
function storeCopyrightContent(string $value, string $channel, string $locale): void
{
    CoreConfig::query()->create([
        'code' => copyrightContentField(),
        'value' => $value,
        'channel_code' => $channel,
        'locale_code' => $locale,
    ]);

    CacheGeneration::bump(CoreConfigRepository::class);
}

// ============================================================================
// Channel Scope
// ============================================================================

it('should resolve the copyright notice per channel', function () {
    storeCopyrightContent('First channel notice', 'first', 'en');
    storeCopyrightContent('Second channel notice', 'second', 'en');

    expect(core()->getConfigData(copyrightContentField(), 'first', 'en'))->toBe('First channel notice')
        ->and(core()->getConfigData(copyrightContentField(), 'second', 'en'))->toBe('Second channel notice');
});

// ============================================================================
// Locale Scope
// ============================================================================

it('should resolve the copyright notice per locale within a channel', function () {
    storeCopyrightContent('English notice', 'first', 'en');
    storeCopyrightContent('German notice', 'first', 'de');

    expect(core()->getConfigData(copyrightContentField(), 'first', 'en'))->toBe('English notice')
        ->and(core()->getConfigData(copyrightContentField(), 'first', 'de'))->toBe('German notice');
});
