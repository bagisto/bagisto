<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Filesystem\StorageSynchronizer;

/**
 * Two disks standing in for the local one and the configured remote.
 */
function fakeStorageDisks(): StorageSynchronizer
{
    Storage::fake('from');

    Storage::fake('to');

    return app(StorageSynchronizer::class);
}

// ============================================================================
// Copying Onto The Configured Disk
// ============================================================================

it('should copy every file the target disk does not have', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'front');
    Storage::disk('from')->put('category/2/banner.webp', 'banner');

    $summary = $synchronizer->sync('from', 'to');

    expect($summary)
        ->total->toBe(2)
        ->copied->toBe(2)
        ->skipped->toBe(0)
        ->failed->toBe(0);

    expect(Storage::disk('to')->get('products/1/front.webp'))->toBe('front')
        ->and(Storage::disk('to')->get('category/2/banner.webp'))->toBe('banner');
});

it('should leave the source files where they are', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'front');

    $synchronizer->sync('from', 'to');

    expect(Storage::disk('from')->exists('products/1/front.webp'))->toBeTrue();
});

it('should pass over a file the target already holds rather than rewriting it', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'newer');
    Storage::disk('to')->put('products/1/front.webp', 'already there');

    $summary = $synchronizer->sync('from', 'to');

    expect($summary)
        ->copied->toBe(0)
        ->skipped->toBe(1)
        ->and(Storage::disk('to')->get('products/1/front.webp'))->toBe('already there');
});

it('should copy nothing on a second run, so an interrupted sync is resumed by repeating it', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'front');

    $synchronizer->sync('from', 'to');

    expect($synchronizer->sync('from', 'to'))
        ->copied->toBe(0)
        ->skipped->toBe(1);
});

it('should do nothing at all when the source and the target are the same disk', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'front');

    expect($synchronizer->sync('from', 'from'))
        ->total->toBe(0)
        ->copied->toBe(0);
});

// ============================================================================
// Reporting Without Writing
// ============================================================================

it('should report what it would copy without writing anything when pretending', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'front');

    expect($synchronizer->sync('from', 'to', true))->copied->toBe(1)
        ->and(Storage::disk('to')->exists('products/1/front.webp'))->toBeFalse();
});

it('should count the files the target is missing', function () {
    $synchronizer = fakeStorageDisks();

    Storage::disk('from')->put('products/1/front.webp', 'front');
    Storage::disk('from')->put('products/1/back.webp', 'back');
    Storage::disk('to')->put('products/1/back.webp', 'back');

    expect($synchronizer->pending('from', 'to'))->toBe(1);
});
