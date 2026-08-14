<?php

/**
 * Guards the icon fonts against the two ways a glyph goes wrong silently.
 *
 * An icon is a class that sets `content` on `::before`, so a name that is never declared
 * paints nothing at all — no error, no warning, just an empty space where the glyph was
 * meant to be. The reverse is quieter still: a glyph declared and used nowhere ships as
 * dead CSS forever.
 *
 * Neither shows up in a build, so they are asserted here instead.
 */

/**
 * The repository root, resolved from this file rather than the application.
 */
function iconBasePath(string $path): string
{
    return dirname(__DIR__, 2).'/'.$path;
}

/**
 * The packages that carry an icon font, and the roots their stylesheet scans.
 */
const ICON_PACKAGES = [
    'Admin' => 'packages/Webkul/Admin',
    'Shop' => 'packages/Webkul/Shop',
    'Installer' => 'packages/Webkul/Installer',
];

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
 * Return the icons the stylesheet safelists, for the ones no source file can name.
 */
function safelistedIcons(string $path): array
{
    if (! preg_match('/@source inline\("\{([^}]*)\}"\);/', iconStylesheet($path), $matches)) {
        return [];
    }

    return explode(',', $matches[1]);
}

/**
 * Return every icon named by a class attribute anywhere in the package, with its variants
 * and important marker stripped — `peer-checked:icon-checked` counts as `icon-checked`.
 */
function referencedIcons(string $path): array
{
    $referenced = [];

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(iconBasePath($path.'/src'))
    );

    foreach ($files as $file) {
        if (! in_array($file->getExtension(), ['php', 'js'])) {
            continue;
        }

        /**
         * A seeder writes markup for another package's theme — the installer seeds the
         * storefront's content — so the icons it names belong to that package, not this one.
         */
        if (str_contains($file->getPathname(), '/Database/Seeders/')) {
            continue;
        }

        $contents = file_get_contents($file->getPathname());

        preg_match_all('/class="([^"]*)"/', $contents, $attributes);

        foreach ($attributes[1] as $attribute) {
            preg_match_all('/(?:[a-z0-9-]+:)*(icon-[A-Za-z0-9-]+)!?/', $attribute, $names);

            $referenced = array_merge($referenced, $names[1]);
        }

        /**
         * Config and datagrid files name an icon as a bare string rather than in a class
         * attribute — the admin menu and the storefront datagrids both do. A string that
         * is a key rather than a value is not one: `'icon-class' => 'promotion-icon'`
         * names a menu setting, not a glyph.
         */
        preg_match_all("/'(icon-[A-Za-z0-9-]+)'(?!\s*=>)/", $contents, $strings);

        $referenced = array_merge($referenced, $strings[1]);

        /**
         * A script may name an icon on its own to toggle it, rather than writing it into a
         * class attribute the markup already carries.
         */
        preg_match_all('/"(icon-[A-Za-z0-9-]+)"/', $contents, $scripts);

        $referenced = array_merge($referenced, $scripts[1]);
    }

    return array_values(array_unique($referenced));
}

it('declares every icon the package uses', function (string $path) {
    $undeclared = array_diff(referencedIcons($path), declaredIcons($path));

    expect($undeclared)->toBeEmpty(
        'These icons are used but never declared, so they render blank: '.implode(', ', $undeclared)
    );
})->with(ICON_PACKAGES);

it('uses every icon the package declares', function (string $path) {
    /**
     * A safelisted icon is named from outside the scanned root — by another package's view
     * or by seeded content — so it is exempt from having a reference in this package.
     */
    $unused = array_diff(declaredIcons($path), referencedIcons($path), safelistedIcons($path));

    expect($unused)->toBeEmpty(
        'These icons are declared but used nowhere, so they ship as dead CSS: '.implode(', ', $unused)
    );
})->with(ICON_PACKAGES);

it('declares every icon it safelists', function (string $path) {
    $undeclared = array_diff(safelistedIcons($path), declaredIcons($path));

    expect($undeclared)->toBeEmpty(
        'These icons are safelisted but never declared: '.implode(', ', $undeclared)
    );
})->with(ICON_PACKAGES);

it('names no icon with a numeric suffix', function (string $path) {
    $suffixed = preg_grep('/-\d+$/', declaredIcons($path));

    expect($suffixed)->toBeEmpty(
        'An icon should be named for what it depicts, not numbered: '.implode(', ', $suffixed)
    );
})->with(ICON_PACKAGES);
