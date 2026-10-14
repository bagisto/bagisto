<?php

use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

/**
 * The packages Core may not depend on, per the Core boundary rule in `AGENTS.md`.
 */
const CORE_BOUNDARY_FEATURE_PACKAGES = [
    'Admin', 'Attribute', 'BookingProduct', 'CMS', 'CartRule', 'CatalogRule', 'Category',
    'Checkout', 'Customer', 'DataGrid', 'DataTransfer', 'DebugBar', 'EUWithdrawal', 'FPC',
    'GDPR', 'ImageCache', 'Installer', 'Inventory', 'MagicAI', 'Marketing', 'Notification',
    'Omnibus', 'PayGlocal', 'PayU', 'Payment', 'Paypal', 'PhonePe', 'Product', 'RMA', 'Razorpay',
    'Rule', 'Sales', 'Shipping', 'Shop', 'Sitemap', 'SocialLogin', 'SocialShare', 'Stripe',
    'Tax', 'Theme', 'User',
];

/**
 * Known debt, kept so the test locks in no *new* violation while an entry is worked off.
 */
const CORE_BOUNDARY_PENDING = [];

/**
 * Core's runtime source, less the factories and seeders that only development and install use.
 */
function coreRuntimeFiles(): array
{
    $root = dirname(__DIR__, 2).'/packages/Webkul/Core/src';

    $files = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
        if ($file->getExtension() !== 'php') {
            continue;
        }

        $relative = str_replace($root.'/', 'src/', $file->getPathname());

        if (str_starts_with($relative, 'src/Database/Factories')
            || str_starts_with($relative, 'src/Database/Seeders')
        ) {
            continue;
        }

        $files[$relative] = $file->getPathname();
    }

    ksort($files);

    return $files;
}

/**
 * Every Webkul class a file names, read from its syntax tree so strings cannot count as references.
 */
function webkulReferences(string $path): array
{
    $ast = (new ParserFactory)->createForNewestSupportedVersion()->parse(file_get_contents($path));

    $finder = new NodeFinder;

    $names = [];

    foreach ($finder->findInstanceOf($ast, Node\UseItem::class) as $use) {
        $names[] = $use->name->toString();
    }

    foreach ($finder->findInstanceOf($ast, Node\Name\FullyQualified::class) as $name) {
        $names[] = $name->toString();
    }

    return array_values(array_unique(array_filter(
        $names,
        fn (string $name) => str_starts_with($name, 'Webkul\\')
    )));
}

/**
 * Whether Core is allowed to name this class.
 */
function coreMayReference(string $relative, string $class): bool
{
    [, $package] = explode('\\', $class) + [1 => ''];

    if (! in_array($package, CORE_BOUNDARY_FEATURE_PACKAGES, true)) {
        return true;
    }

    if (str_ends_with($class, 'Proxy')) {
        return true;
    }

    return in_array($class, CORE_BOUNDARY_PENDING[$relative] ?? [], true);
}

// ============================================================================
// Core Package Boundary
// ============================================================================

it('should not let Core depend on a feature package', function () {
    $violations = [];

    foreach (coreRuntimeFiles() as $relative => $path) {
        foreach (webkulReferences($path) as $class) {
            if (! coreMayReference($relative, $class)) {
                [, $package] = explode('\\', $class);

                $violations[] = "Core/{$relative} references {$package} via {$class}";
            }
        }
    }

    expect($violations)->toBe([], "Core must not depend on a feature package:\n  ".implode("\n  ", $violations));
});

it('should keep the pending list honest, with no entry left behind', function () {
    $stale = [];

    foreach (CORE_BOUNDARY_PENDING as $relative => $classes) {
        $path = dirname(__DIR__, 2).'/packages/Webkul/Core/'.$relative;

        $referenced = is_file($path) ? webkulReferences($path) : [];

        foreach ($classes as $class) {
            if (! in_array($class, $referenced, true)) {
                $stale[] = "{$relative} no longer references {$class}";
            }
        }
    }

    expect($stale)->toBe([], "Remove these from CORE_BOUNDARY_PENDING:\n  ".implode("\n  ", $stale));
});
