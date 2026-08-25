<?php

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Webkul\Theme\ThemeStorage;

// ============================================================================
// Normalizing
// ============================================================================

it('reduces a stored value to the path it names on the disk', function (?string $stored, ?string $expected) {
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

it('resolves a stored path through the configured disk', function () {
    expect(bagisto_theme_storage()->url('themes/default/sections/1/a.webp'))
        ->toBe(Storage::url('themes/default/sections/1/a.webp'));
});

it('resolves a path recorded with the old storage prefix to the same url', function () {
    expect(bagisto_theme_storage()->url('storage/themes/default/sections/1/a.webp'))
        ->toBe(bagisto_theme_storage()->url('themes/default/sections/1/a.webp'));
});

it('serves resized urls from the image cache route on a local disk', function () {
    expect(Storage::getAdapter())->toBeInstanceOf(LocalFilesystemAdapter::class);

    expect(bagisto_theme_storage()->resizedUrl('themes/default/sections/1/a.webp', 'large'))
        ->toBe(url('cache/large/themes/default/sections/1/a.webp'));
});

it('hands back every size alongside the original', function () {
    $urls = bagisto_theme_storage()->imageUrls('themes/default/sections/1/a.webp');

    expect($urls['url'])->toBe(Storage::url('themes/default/sections/1/a.webp'))
        ->and(array_keys($urls['srcset']))->toBe(ThemeStorage::SIZES);
});

it('leaves an absolute url alone rather than resolving it', function () {
    expect(bagisto_theme_storage()->url('https://cdn.example.com/a.webp'))->toBe('https://cdn.example.com/a.webp')
        ->and(bagisto_theme_storage()->resizedUrl('https://cdn.example.com/a.webp', 'large'))->toBe('https://cdn.example.com/a.webp');
});

it('resolves a stored video the same way it resolves an image', function () {
    expect(bagisto_theme_storage()->url('themes/default/sections/1/clip.mp4'))
        ->toBe(Storage::url('themes/default/sections/1/clip.mp4'));
});

it('writes a domain free url into authored markup, so custom html survives a move', function () {
    $embedded = bagisto_theme_storage()->embedUrl('themes/default/sections/1/clip.mp4');

    expect($embedded)->toBe('/storage/themes/default/sections/1/clip.mp4')
        ->and($embedded)->not->toContain(config('app.url'))
        ->and($embedded)->not->toStartWith('http');
});

it('writes the same markup url whichever way the path was recorded', function () {
    expect(bagisto_theme_storage()->embedUrl('storage/themes/default/sections/1/a.webp'))
        ->toBe(bagisto_theme_storage()->embedUrl('themes/default/sections/1/a.webp'));
});

it('leaves an absolute url alone when writing it into markup', function () {
    expect(bagisto_theme_storage()->embedUrl('https://cdn.example.com/a.webp'))
        ->toBe('https://cdn.example.com/a.webp');
});

it('resolves nothing when no path was recorded', function () {
    expect(bagisto_theme_storage()->url(null))->toBeNull()
        ->and(bagisto_theme_storage()->resizedUrl('', 'large'))->toBeNull()
        ->and(bagisto_theme_storage()->imageUrls(null))->toBeNull();
});

// ============================================================================
// Resolving On A Remote Disk
// ============================================================================

it('writes the full disk url into markup when the disk is not local', function () {
    Storage::shouldReceive('getAdapter')->andReturn(new stdClass);

    Storage::shouldReceive('url')
        ->with('themes/default/sections/1/clip.mp4')
        ->andReturn('https://bucket.s3.amazonaws.com/themes/default/sections/1/clip.mp4');

    expect(app(ThemeStorage::class)->embedUrl('themes/default/sections/1/clip.mp4'))
        ->toBe('https://bucket.s3.amazonaws.com/themes/default/sections/1/clip.mp4');
});

it('still offers a resized copy when the disk is not local, which the cache route reads through', function () {
    Storage::shouldReceive('getAdapter')->andReturn(new stdClass);

    expect(app(ThemeStorage::class)->resizedUrl('themes/default/sections/1/a.webp', 'large'))
        ->toBe(url('cache/large/themes/default/sections/1/a.webp'));
});
