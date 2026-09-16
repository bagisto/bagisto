<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Helpers\MediaFileName;

beforeEach(function () {
    Storage::fake();

    $this->mediaFileName = app(MediaFileName::class);
});

// ============================================================================
// Sanitizing
// ============================================================================

it('should slug the requested name', function () {
    expect($this->mediaFileName->sanitize('Blue Running Shoe — Side'))->toBe('blue-running-shoe-side');
});

it('should drop any directory component from the requested name', function () {
    expect($this->mediaFileName->sanitize('../../../etc/passwd'))->toBe('passwd');
});

it('should fall back to a random name when nothing sluggable is left', function () {
    expect($this->mediaFileName->sanitize('!!!'))->toHaveLength(40)
        ->and($this->mediaFileName->sanitize(null))->toHaveLength(40);
});

it('should cap the length of the requested name', function () {
    expect($this->mediaFileName->sanitize(str_repeat('a', 400)))->toHaveLength(MediaFileName::MAX_LENGTH);
});

// ============================================================================
// Resolving
// ============================================================================

it('should keep the extension dictated by the caller, not the one in the requested name', function () {
    expect($this->mediaFileName->resolve('product/1', 'payload.php', 'webp'))->toBe('product/1/payload.webp');
});

it('should suffix the name when the resolved path is already taken', function () {
    Storage::put('product/1/blue-shoe.webp', 'first');

    expect($this->mediaFileName->resolve('product/1', 'blue-shoe', 'webp'))->toBe('product/1/blue-shoe-1.webp');

    Storage::put('product/1/blue-shoe-1.webp', 'second');

    expect($this->mediaFileName->resolve('product/1', 'blue-shoe', 'webp'))->toBe('product/1/blue-shoe-2.webp');
});

// ============================================================================
// Renaming
// ============================================================================

it('should move the file when renaming', function () {
    Storage::put($current = 'product/1/hf83nd.webp', 'contents');

    $renamed = $this->mediaFileName->rename($current, 'Blue Shoe');

    expect($renamed)->toBe('product/1/blue-shoe.webp')
        ->and(Storage::get($renamed))->toBe('contents');

    Storage::assertMissing($current);
});

it('should keep the current path when the requested name is empty', function () {
    Storage::put($current = 'product/1/hf83nd.webp', 'contents');

    expect($this->mediaFileName->rename($current, null))->toBe($current)
        ->and($this->mediaFileName->rename($current, ''))->toBe($current);

    Storage::assertExists($current);
});

it('should keep the current path when the requested name resolves to the current one', function () {
    Storage::put($current = 'product/1/blue-shoe.webp', 'contents');

    expect($this->mediaFileName->rename($current, 'blue-shoe'))->toBe($current)
        ->and($this->mediaFileName->rename($current, 'Blue Shoe.webp'))->toBe($current);

    Storage::assertExists($current);
});

it('should keep the current path when the file is not on disk', function () {
    expect($this->mediaFileName->rename('product/1/gone.webp', 'blue-shoe'))->toBe('product/1/gone.webp');
});

it('should never rename a file out of its own directory', function () {
    Storage::put($current = 'product/1/hf83nd.webp', 'contents');

    expect($this->mediaFileName->rename($current, '../../evil'))->toBe('product/1/evil.webp');

    Storage::assertExists('product/1/evil.webp');
});

// ============================================================================
// Upload Extensions
// ============================================================================

it('should store an upload under the extension detected from its contents', function () {
    $upload = $this->uploadedFileWithContents('shell.php', UploadedFile::fake()->image('photo.png', 10, 10)->get());

    expect($this->mediaFileName->extension($upload))->toBe('png');
});

it('should fall back to the client extension when the contents are not recognised but the extension is allowed', function () {
    expect($this->mediaFileName->extension($this->uploadedFileWithContents('clip.mp4', random_bytes(64))))->toBe('mp4');
});

it('should never store an upload under an extension outside the allowed ones', function (string $name, string $contents) {
    expect($this->mediaFileName->extension($this->uploadedFileWithContents($name, $contents)))
        ->toBe(MediaFileName::FALLBACK_EXTENSION);
})->with([
    'a page' => ['page.html', '<html><script>alert(1)</script></html>'],
    'a script' => ['shell.php', '<?php echo 1;'],
]);
