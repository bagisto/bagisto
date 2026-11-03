<?php

use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\get;

/**
 * The key the storefront keeps the category tree of a locale under in the browser.
 */
function categoriesStorageKey(string $localeCode): string
{
    return 'categories:'.core()->getCurrentChannelCode().':'.$localeCode;
}

/**
 * Add a locale to the current channel, as the admin does when a store offers another language.
 */
function localeOfferedByTheChannel(): Locale
{
    $locale = Locale::factory()->create();

    core()->getCurrentChannel()->locales()->attach($locale);

    Core::clearResolvedInstance(BaseCore::class);

    return $locale;
}

// ============================================================================
// Category Tree Cache
// ============================================================================

it('should keep the header category tree in the browser under the key of the channel and the locale', function (string $path, int $uses) {
    $content = get($path)
        ->assertOk()
        ->getContent();

    expect(substr_count($content, categoriesStorageKey(core()->getCurrentChannel()->default_locale->code)))->toBe($uses);
})->with([
    'home page, which also writes the tree' => ['/', 3],
    'any other storefront page' => ['/contact-us', 2],
]);

it('should switch the header category tree with the locale on any page, not only on the home page', function (string $path, int $uses) {
    $defaultLocaleCode = core()->getCurrentChannel()->default_locale->code;

    $locale = localeOfferedByTheChannel();

    $content = get($path.'?locale='.$locale->code)
        ->assertOk()
        ->getContent();

    expect(substr_count($content, categoriesStorageKey($locale->code)))->toBe($uses)
        ->and(substr_count($content, categoriesStorageKey($defaultLocaleCode)))->toBe(0);
})->with([
    'home page, which also writes the tree' => ['/', 3],
    'any other storefront page' => ['/contact-us', 2],
]);
