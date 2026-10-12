<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Traits\Sanitizer;

/**
 * A caller of the trait, holding nothing of its own.
 */
function svgSanitizer(): object
{
    return new class
    {
        use Sanitizer;
    };
}

beforeEach(fn () => Storage::fake());

// ============================================================================
// Sanitizing A Readable File
// ============================================================================

it('should sanitize a readable svg in place and report success', function () {
    Storage::put('uploads/logo.svg', '<svg><rect/></svg>');

    expect(svgSanitizer()->sanitizeSVG('uploads/logo.svg', 'image/svg+xml'))->toBeTrue()
        ->and(Storage::exists('uploads/logo.svg'))->toBeTrue()
        ->and(Storage::get('uploads/logo.svg'))->toContain('<svg');
});

it('should strip a script out of a readable svg', function () {
    Storage::put('uploads/evil.svg', '<svg><script>alert(1)</script><rect/></svg>');

    expect(svgSanitizer()->sanitizeSVG('uploads/evil.svg', 'image/svg+xml'))->toBeTrue()
        ->and(Storage::get('uploads/evil.svg'))->not->toContain('script');
});

// ============================================================================
// Refusing A File That Cannot Be Read
// ============================================================================

it('should refuse an svg that cannot be read and leave no file behind', function (string $svg) {
    Storage::put('uploads/broken.svg', $svg);

    expect(svgSanitizer()->sanitizeSVG('uploads/broken.svg', 'image/svg+xml'))->toBeFalse()
        ->and(Storage::exists('uploads/broken.svg'))->toBeFalse();
})->with([
    'unescaped ampersand' => ['<svg><text>a & b</text></svg>'],
    'unclosed tag' => ['<svg><rect></svg>'],
    'truncated file' => ['<svg><rect wid'],
    'empty file' => [''],
]);

it('should expose the parse issues behind a refusal', function () {
    $sanitizer = svgSanitizer();

    Storage::put('uploads/broken.svg', '<svg><rect></svg>');

    $sanitizer->sanitizeSVG('uploads/broken.svg', 'image/svg+xml');

    expect($sanitizer->svgSanitizeIssues)->not->toBeEmpty();
});

it('should forget the parse issues of an earlier refusal once a file sanitizes', function () {
    $sanitizer = svgSanitizer();

    Storage::put('uploads/broken.svg', '<svg><rect></svg>');

    Storage::put('uploads/logo.svg', '<svg><rect/></svg>');

    $sanitizer->sanitizeSVG('uploads/broken.svg', 'image/svg+xml');

    $sanitizer->sanitizeSVG('uploads/logo.svg', 'image/svg+xml');

    expect($sanitizer->svgSanitizeIssues)->toBeEmpty();
});

// ============================================================================
// Leaving Every Other Type Alone
// ============================================================================

it('should report success and change nothing for a file that is not an svg', function () {
    Storage::put('uploads/photo.png', 'not-really-a-png');

    expect(svgSanitizer()->sanitizeSVG('uploads/photo.png', 'image/png'))->toBeTrue()
        ->and(Storage::exists('uploads/photo.png'))->toBeTrue()
        ->and(Storage::get('uploads/photo.png'))->toBe('not-really-a-png');
});
