<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Helpers\MediaFileName;

beforeEach(function () {
    Storage::fake();

    $this->mediaFileName = app(MediaFileName::class);
});

it('should slug the requested name', function () {
    expect($this->mediaFileName->sanitize('Blue Running Shoe — Side'))->toBe('blue-running-shoe-side');
});

it('should drop any directory component from the requested name', function () {
    expect($this->mediaFileName->sanitize('../../../etc/passwd'))->toBe('passwd');
});

it('should fall back to a random name when nothing sluggable is left', function () {
    expect($this->mediaFileName->sanitize('!!!'))->toHaveLength(40);

    expect($this->mediaFileName->sanitize(null))->toHaveLength(40);
});

it('should cap the length of the requested name', function () {
    expect($this->mediaFileName->sanitize(str_repeat('a', 400)))
        ->toHaveLength(MediaFileName::MAX_LENGTH);
});

it('should keep the extension dictated by the caller, not the one in the requested name', function () {
    expect($this->mediaFileName->resolve('product/1', 'payload.php', 'webp'))
        ->toBe('product/1/payload.webp');
});

it('should suffix the name when the resolved path is already taken', function () {
    Storage::put('product/1/blue-shoe.webp', 'first');

    expect($this->mediaFileName->resolve('product/1', 'blue-shoe', 'webp'))
        ->toBe('product/1/blue-shoe-1.webp');

    Storage::put('product/1/blue-shoe-1.webp', 'second');

    expect($this->mediaFileName->resolve('product/1', 'blue-shoe', 'webp'))
        ->toBe('product/1/blue-shoe-2.webp');
});

it('should move the file when renaming', function () {
    Storage::put($current = 'product/1/hf83nd.webp', 'contents');

    $renamed = $this->mediaFileName->rename($current, 'Blue Shoe');

    expect($renamed)->toBe('product/1/blue-shoe.webp');

    Storage::assertMissing($current);

    Storage::assertExists($renamed);

    expect(Storage::get($renamed))->toBe('contents');
});

it('should keep the current path when the requested name is empty', function () {
    Storage::put($current = 'product/1/hf83nd.webp', 'contents');

    expect($this->mediaFileName->rename($current, null))->toBe($current);

    expect($this->mediaFileName->rename($current, ''))->toBe($current);

    Storage::assertExists($current);
});

it('should keep the current path when the requested name resolves to the current one', function () {
    Storage::put($current = 'product/1/blue-shoe.webp', 'contents');

    expect($this->mediaFileName->rename($current, 'blue-shoe'))->toBe($current);

    expect($this->mediaFileName->rename($current, 'Blue Shoe.webp'))->toBe($current);

    Storage::assertExists($current);
});

it('should keep the current path when the file is not on disk', function () {
    expect($this->mediaFileName->rename('product/1/gone.webp', 'blue-shoe'))
        ->toBe('product/1/gone.webp');
});

it('should never rename a file out of its own directory', function () {
    Storage::put($current = 'product/1/hf83nd.webp', 'contents');

    $renamed = $this->mediaFileName->rename($current, '../../evil');

    expect($renamed)->toBe('product/1/evil.webp');

    Storage::assertExists('product/1/evil.webp');
});
