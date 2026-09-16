<?php

dataset('icon packages', [
    'Admin' => 'packages/Webkul/Admin',
    'Shop' => 'packages/Webkul/Shop',
    'Installer' => 'packages/Webkul/Installer',
]);

/**
 * The repository root, resolved from this file rather than the application.
 */
function iconBasePath(string $path): string
{
    return dirname(__DIR__, 2).'/'.$path;
}

/**
 * Return the stylesheet of the given package.
 */
function iconStylesheet(string $path): string
{
    return file_get_contents(iconBasePath($path.'/src/Resources/assets/css/app.css'));
}

/**
 * Return every icon the stylesheet declares, whether as a theme token or its own utility.
 */
function declaredIcons(string $path): array
{
    $css = iconStylesheet($path);

    preg_match_all('/--(icon-[A-Za-z0-9-]+)\s*:/', $css, $tokens);
    preg_match_all('/@utility (icon-[A-Za-z0-9-]+)\s*\{/', $css, $utilities);

    return array_values(array_unique(array_merge($tokens[1], $utilities[1])));
}

/**
 * Return the icons the stylesheet safelists, which another package's view or seeded content names
 * from outside the scanned root, so they need no reference in the package itself.
 */
function safelistedIcons(string $path): array
{
    if (! preg_match('/@source inline\("\{([^}]*)\}"\);/', iconStylesheet($path), $matches)) {
        return [];
    }

    return explode(',', $matches[1]);
}

/**
 * Whether a file names icons of its own package; a seeder writes markup for another package's theme.
 */
function isIconSourceFile(SplFileInfo $file): bool
{
    return in_array($file->getExtension(), ['php', 'js'])
        && ! str_contains($file->getPathname(), '/Database/Seeders/');
}

/**
 * Return the icons named by the class attributes of a source, with variants and the important marker
 * stripped, so `peer-checked:icon-checked` counts as `icon-checked`.
 */
function iconsInClassAttributes(string $contents): array
{
    preg_match_all('/class="([^"]*)"/', $contents, $attributes);

    $icons = [];

    foreach ($attributes[1] as $attribute) {
        preg_match_all('/(?:[a-z0-9-]+:)*(icon-[A-Za-z0-9-]+)!?/', $attribute, $names);

        $icons = array_merge($icons, $names[1]);
    }

    return $icons;
}

/**
 * Return the icons a source names as bare quoted strings, as menus, datagrids and scripts do,
 * leaving out an array key such as `'icon-class' => 'promotion-icon'`, which names a setting.
 */
function iconsInQuotedStrings(string $contents): array
{
    preg_match_all("/'(icon-[A-Za-z0-9-]+)'(?!\s*=>)/", $contents, $singleQuoted);
    preg_match_all('/"(icon-[A-Za-z0-9-]+)"/', $contents, $doubleQuoted);

    return array_merge($singleQuoted[1], $doubleQuoted[1]);
}

/**
 * Return every icon named anywhere in the package, whether in a class attribute or a quoted string.
 */
function referencedIcons(string $path): array
{
    $referenced = [];

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(iconBasePath($path.'/src'))
    );

    foreach ($files as $file) {
        if (! isIconSourceFile($file)) {
            continue;
        }

        $contents = file_get_contents($file->getPathname());

        $referenced = array_merge($referenced, iconsInClassAttributes($contents), iconsInQuotedStrings($contents));
    }

    return array_values(array_unique($referenced));
}

// ============================================================================
// Icon Fonts
// ============================================================================

it('should declare every icon the package uses', function (string $path) {
    $undeclared = array_diff(referencedIcons($path), declaredIcons($path));

    expect($undeclared)->toBeEmpty(
        'These icons are used but never declared, so they render blank: '.implode(', ', $undeclared)
    );
})->with('icon packages');

it('should use every icon the package declares', function (string $path) {
    $unused = array_diff(declaredIcons($path), referencedIcons($path), safelistedIcons($path));

    expect($unused)->toBeEmpty(
        'These icons are declared but used nowhere, so they ship as dead CSS: '.implode(', ', $unused)
    );
})->with('icon packages');

it('should declare every icon it safelists', function (string $path) {
    $undeclared = array_diff(safelistedIcons($path), declaredIcons($path));

    expect($undeclared)->toBeEmpty(
        'These icons are safelisted but never declared: '.implode(', ', $undeclared)
    );
})->with('icon packages');

it('should name no icon with a numeric suffix', function (string $path) {
    $suffixed = preg_grep('/-\d+$/', declaredIcons($path));

    expect($suffixed)->toBeEmpty(
        'An icon should be named for what it depicts, not numbered: '.implode(', ', $suffixed)
    );
})->with('icon packages');
