<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Helpers\MediaFileName;

/**
 * Build a real upload, whose type is detected from its contents rather than from its name as a fake one's is,
 * in a temporary file removed when the run ends.
 */
function uploadedFileNamed(string $name, string $contents): UploadedFile
{
    static $handles = [];

    $handles[] = $handle = tmpfile();

    fwrite($handle, $contents);

    return new UploadedFile(stream_get_meta_data($handle)['uri'], $name, null, null, true);
}

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

it('should store an upload under the extension detected from its contents', function () {
    $image = UploadedFile::fake()->image('photo.png', 10, 10);

    expect($this->mediaFileName->extension(uploadedFileNamed('shell.php', $image->get())))->toBe('png');
});

it('should fall back to the client extension when the contents are not recognised but the extension is allowed', function () {
    expect($this->mediaFileName->extension(uploadedFileNamed('clip.mp4', random_bytes(64))))->toBe('mp4');
});

it('should never store an upload under an extension outside the allowed ones', function () {
    expect($this->mediaFileName->extension(uploadedFileNamed('page.html', '<html><script>alert(1)</script></html>')))
        ->toBe(MediaFileName::FALLBACK_EXTENSION);

    expect($this->mediaFileName->extension(uploadedFileNamed('shell.php', '<?php echo 1;')))
        ->toBe(MediaFileName::FALLBACK_EXTENSION);
});
