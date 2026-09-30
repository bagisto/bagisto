<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Webkul\Theme\ThemeStorage;

/**
 * Serve the application from a directory below the document root, as a dev box maps it.
 */
function serveFromSubdirectory(string $appUrl): void
{
    config([
        'app.url' => $appUrl,
        'filesystems.disks.public.url' => $appUrl.'/storage',
    ]);

    $request = Request::create('http://127.0.0.1/folder-1/bagisto/public/', 'GET', [], [], [], [
        'SCRIPT_NAME' => '/folder-1/bagisto/public/index.php',
        'SCRIPT_FILENAME' => '/var/www/html/folder-1/bagisto/public/index.php',
        'PHP_SELF' => '/folder-1/bagisto/public/index.php',
    ]);

    app()->instance('request', $request);

    URL::setRequest($request);
}

/**
 * The two ways `app.url` is written when the application sits in a subdirectory.
 */
dataset('app url in a subdirectory', [
    'app url carries the directory' => 'http://127.0.0.1/folder-1/bagisto/public',
    'app url carries only the host' => 'http://127.0.0.1',
]);

// ============================================================================
// Normalizing
// ============================================================================

it('should reduce a stored value to the path it names on the disk', function (?string $stored, ?string $expected) {
    expect(bagisto_theme_storage()->normalize($stored))->toBe($expected);
})->with([
    'bare path' => ['themes/default/sections/1/a.webp', 'themes/default/sections/1/a.webp'],
    'legacy prefix' => ['storage/themes/default/sections/1/a.webp', 'themes/default/sections/1/a.webp'],
    'leading slash' => ['/storage/themes/default/sections/1/a.webp', 'themes/default/sections/1/a.webp'],
    'absolute url' => ['https://cdn.example.com/a.webp', 'https://cdn.example.com/a.webp'],
    'empty' => ['', null],
    'whitespace' => ['   ', null],
    'null' => [null, null],
]);

// ============================================================================
// Resolving On A Local Disk
// ============================================================================

it('should resolve a stored path through the configured disk', function () {
    expect(bagisto_theme_storage()->url('themes/default/sections/1/a.webp'))
        ->toBe(Storage::url('themes/default/sections/1/a.webp'));
});

it('should resolve a path recorded with the old storage prefix to the same url', function () {
    expect(bagisto_theme_storage()->url('storage/themes/default/sections/1/a.webp'))
        ->toBe(bagisto_theme_storage()->url('themes/default/sections/1/a.webp'));
});

it('should serve resized urls from the image cache route on a local disk', function () {
    expect(Storage::getAdapter())->toBeInstanceOf(LocalFilesystemAdapter::class)
        ->and(bagisto_theme_storage()->resizedUrl('themes/default/sections/1/a.webp', 'large'))
        ->toBe(url('cache/large/themes/default/sections/1/a.webp'));
});

it('should hand back every size alongside the original', function () {
    $urls = bagisto_theme_storage()->imageUrls('themes/default/sections/1/a.webp');

    expect($urls['url'])->toBe(Storage::url('themes/default/sections/1/a.webp'))
        ->and(array_keys($urls['srcset']))->toBe(ThemeStorage::SIZES);
});

it('should leave an absolute url alone rather than resolving it', function () {
    expect(bagisto_theme_storage()->url('https://cdn.example.com/a.webp'))->toBe('https://cdn.example.com/a.webp')
        ->and(bagisto_theme_storage()->resizedUrl('https://cdn.example.com/a.webp', 'large'))->toBe('https://cdn.example.com/a.webp');
});

it('should resolve a stored video the same way it resolves an image', function () {
    expect(bagisto_theme_storage()->url('themes/default/sections/1/clip.mp4'))
        ->toBe(Storage::url('themes/default/sections/1/clip.mp4'));
});

it('should write a domain free url into authored markup, so custom html survives a move', function () {
    $embedded = bagisto_theme_storage()->embedUrl('themes/default/sections/1/clip.mp4');

    expect($embedded)->toBe('/storage/themes/default/sections/1/clip.mp4')
        ->and($embedded)->not->toContain(config('app.url'))
        ->and($embedded)->not->toStartWith('http');
});

it('should write the same markup url whichever way the path was recorded', function () {
    expect(bagisto_theme_storage()->embedUrl('storage/themes/default/sections/1/a.webp'))
        ->toBe(bagisto_theme_storage()->embedUrl('themes/default/sections/1/a.webp'));
});

it('should leave an absolute url alone when writing it into markup', function () {
    expect(bagisto_theme_storage()->embedUrl('https://cdn.example.com/a.webp'))
        ->toBe('https://cdn.example.com/a.webp');
});

it('should resolve nothing when no path was recorded', function () {
    expect(bagisto_theme_storage()->url(null))->toBeNull()
        ->and(bagisto_theme_storage()->resizedUrl('', 'large'))->toBeNull()
        ->and(bagisto_theme_storage()->imageUrls(null))->toBeNull();
});

// ============================================================================
// Resolving On A Remote Disk
// ============================================================================

it('should write the full disk url into markup when the disk is not local', function () {
    Storage::shouldReceive('getAdapter')->andReturn(new stdClass);

    Storage::shouldReceive('url')
        ->with('themes/default/sections/1/clip.mp4')
        ->andReturn('https://bucket.s3.amazonaws.com/themes/default/sections/1/clip.mp4');

    expect(app(ThemeStorage::class)->embedUrl('themes/default/sections/1/clip.mp4'))
        ->toBe('https://bucket.s3.amazonaws.com/themes/default/sections/1/clip.mp4');
});

it('should still offer a resized copy when the disk is not local, which the cache route reads through', function () {
    Storage::shouldReceive('getAdapter')->andReturn(new stdClass);

    expect(app(ThemeStorage::class)->resizedUrl('themes/default/sections/1/a.webp', 'large'))
        ->toBe(url('cache/large/themes/default/sections/1/a.webp'));
});

// ============================================================================
// Media References In Authored Markup
// ============================================================================

it('should write a reference rather than a url into authored markup', function () {
    expect(bagisto_theme_storage()->mediaReference('themes/default/sections/1/a.webp'))
        ->toBe(ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp');
});

it('should write the same reference whichever way the path was recorded', function (string $stored) {
    expect(bagisto_theme_storage()->mediaReference($stored))
        ->toBe(ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp');
})->with([
    'bare path' => 'themes/default/sections/1/a.webp',
    'legacy prefix' => 'storage/themes/default/sections/1/a.webp',
    'leading slash' => '/storage/themes/default/sections/1/a.webp',
]);

it('should leave an absolute url alone rather than referencing it', function () {
    expect(bagisto_theme_storage()->mediaReference('https://cdn.example.com/a.webp'))
        ->toBe('https://cdn.example.com/a.webp');
});

it('should reference nothing when no path was recorded', function () {
    expect(bagisto_theme_storage()->mediaReference(null))->toBeNull()
        ->and(bagisto_theme_storage()->mediaReference(''))->toBeNull();
});

it('should resolve a reference in markup to the url it is served from', function () {
    $markup = '<img data-src="'.ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp" alt="">';

    expect(bagisto_theme_storage()->resolveMarkup($markup))
        ->toBe('<img data-src="/storage/themes/default/sections/1/a.webp" alt="">');
});

it('should resolve a reference inside a stylesheet', function () {
    $css = '.hero{background-image:url('.ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp)}';

    expect(bagisto_theme_storage()->resolveMarkup($css))
        ->toBe('.hero{background-image:url(/storage/themes/default/sections/1/a.webp)}');
});

it('should resolve a url written before markup kept references', function (string $authored) {
    $markup = '<img src="'.$authored.'" alt="">';

    expect(bagisto_theme_storage()->resolveMarkup($markup))
        ->toBe('<img src="/storage/themes/default/sections/1/a.webp" alt="">');
})->with([
    'addressed from the site root' => '/storage/themes/default/sections/1/a.webp',
    'addressed relative to the page' => 'storage/themes/default/sections/1/a.webp',
]);

it('should resolve a url written before markup kept references inside a stylesheet', function (string $authored) {
    expect(bagisto_theme_storage()->resolveMarkup('.hero{background-image:url('.$authored.')}'))
        ->toBe('.hero{background-image:url(/storage/themes/default/sections/1/a.webp)}');
})->with([
    'addressed from the site root' => '/storage/themes/default/sections/1/a.webp',
    'addressed relative to the page' => 'storage/themes/default/sections/1/a.webp',
]);

it('should leave a storage path on another host alone', function () {
    $markup = '<img src="https://cdn.example.com/storage/themes/default/sections/1/a.webp" alt="">';

    expect(bagisto_theme_storage()->resolveMarkup($markup))->toBe($markup);
});

it('should leave markup without a reference untouched', function (string $markup) {
    expect(bagisto_theme_storage()->resolveMarkup($markup))->toBe($markup);
})->with([
    'external image' => '<img src="https://cdn.example.com/a.webp" alt="">',
    'plain link' => '<a href="/page/about-us">About</a>',
    'no media at all' => '<p>Nothing to resolve here.</p>',
]);

it('should resolve nothing out of empty markup', function () {
    expect(bagisto_theme_storage()->resolveMarkup(null))->toBe('')
        ->and(bagisto_theme_storage()->resolveMarkup(''))->toBe('');
});

// ============================================================================
// Serving From A Subdirectory
// ============================================================================

it('should resolve a stored path under the directory the application is served from', function (string $appUrl) {
    serveFromSubdirectory($appUrl);

    expect(bagisto_theme_storage()->url('themes/default/sections/1/a.webp'))
        ->toBe('http://127.0.0.1/folder-1/bagisto/public/storage/themes/default/sections/1/a.webp');
})->with('app url in a subdirectory');

it('should resolve a resized copy under the directory the application is served from', function (string $appUrl) {
    serveFromSubdirectory($appUrl);

    expect(bagisto_theme_storage()->resizedUrl('themes/default/sections/1/a.webp', 'large'))
        ->toBe('http://127.0.0.1/folder-1/bagisto/public/cache/large/themes/default/sections/1/a.webp');
})->with('app url in a subdirectory');

it('should keep the directory the application is served from in a markup url', function (string $appUrl) {
    serveFromSubdirectory($appUrl);

    expect(bagisto_theme_storage()->embedUrl('themes/default/sections/1/a.webp'))
        ->toBe('/folder-1/bagisto/public/storage/themes/default/sections/1/a.webp');
})->with('app url in a subdirectory');

it('should resolve the media base the editor is given under that directory', function (string $appUrl) {
    serveFromSubdirectory($appUrl);

    expect(bagisto_theme_storage()->base())
        ->toBe('http://127.0.0.1/folder-1/bagisto/public/storage/');
})->with('app url in a subdirectory');

it('should resolve a reference in markup under the directory the application is served from', function (string $appUrl) {
    serveFromSubdirectory($appUrl);

    $markup = '<img data-src="'.ThemeStorage::MEDIA_REFERENCE.'themes/default/sections/1/a.webp" alt="">';

    expect(bagisto_theme_storage()->resolveMarkup($markup))
        ->toBe('<img data-src="/folder-1/bagisto/public/storage/themes/default/sections/1/a.webp" alt="">');
})->with('app url in a subdirectory');

it('should resolve a url written before markup kept references under that directory too', function (string $appUrl) {
    serveFromSubdirectory($appUrl);

    $markup = '<img src="/storage/themes/default/sections/1/a.webp" alt="">';

    expect(bagisto_theme_storage()->resolveMarkup($markup))
        ->toBe('<img src="/folder-1/bagisto/public/storage/themes/default/sections/1/a.webp" alt="">');
})->with('app url in a subdirectory');

it('should keep a reference pointing at the same file however the application is served', function (string $appUrl) {
    $reference = bagisto_theme_storage()->mediaReference('themes/default/sections/1/a.webp');

    serveFromSubdirectory($appUrl);

    expect(bagisto_theme_storage()->mediaReference('themes/default/sections/1/a.webp'))
        ->toBe($reference);
})->with('app url in a subdirectory');
