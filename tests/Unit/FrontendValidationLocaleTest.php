<?php

use Webkul\Core\Helpers\SupportedLocales;

/**
 * The Vite builds that register client-side validation messages, each with its own plugin, so a
 * locale added to `SupportedLocales` has to reach all three.
 */
const FRONTEND_VALIDATION_PLUGINS = [
    'Admin' => 'packages/Webkul/Admin/src/Resources/assets/js/plugins/vee-validate.js',
    'Shop' => 'packages/Webkul/Shop/src/Resources/assets/js/plugins/vee-validate.js',
    'Installer' => 'packages/Webkul/Installer/src/Resources/assets/js/plugins/vee-validate.js',
];

/**
 * The locale codes a plugin registers with `localize()`, read from the `code: { ...code, }`
 * shape every entry in that map uses.
 */
function registeredValidationLocales(string $relativePath): array
{
    $source = file_get_contents(dirname(__DIR__, 2).'/'.$relativePath);

    preg_match_all('/(\w+):\s*\{\s*\.\.\.\1\s*,/', $source, $matches);

    return array_values(array_unique($matches[1]));
}

// ============================================================================
// Client-Side Validation Locales
// ============================================================================

it('should register every supported locale in each frontend validation build', function (string $package, string $path) {
    $missing = array_values(array_diff(SupportedLocales::codes(), registeredValidationLocales($path)));

    expect($missing)->toBe(
        [],
        "{$package} does not register client-side validation messages for: ".implode(', ', $missing)
    );
})->with(array_map(
    fn ($package, $path) => [$package, $path],
    array_keys(FRONTEND_VALIDATION_PLUGINS),
    FRONTEND_VALIDATION_PLUGINS
));

it('should not register a locale that Bagisto does not ship', function (string $package, string $path) {
    $unknown = array_values(array_diff(registeredValidationLocales($path), SupportedLocales::codes()));

    expect($unknown)->toBe(
        [],
        "{$package} registers client-side validation messages for an unsupported locale: ".implode(', ', $unknown)
    );
})->with(array_map(
    fn ($package, $path) => [$package, $path],
    array_keys(FRONTEND_VALIDATION_PLUGINS),
    FRONTEND_VALIDATION_PLUGINS
));
