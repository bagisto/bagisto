<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\get;

beforeEach(function () {
    File::ensureDirectoryExists(storage_path('app/public/imagecache-responses'));
});

afterEach(function () {
    File::deleteDirectory(storage_path('app/public/imagecache-responses'));
});

// ============================================================================
// Serving Images
// ============================================================================

it('should serve a stored image as its own image type, which the browser may neither sniff nor run', function (string $template) {
    File::put(storage_path('app/public/imagecache-responses/source.png'), UploadedFile::fake()->image('source.png', 20, 10)->getContent());

    get('cache/'.$template.'/imagecache-responses/source.png')
        ->assertOk()
        ->assertHeader('Content-Type', 'image/png')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Content-Security-Policy', "default-src 'none'; style-src 'unsafe-inline'; sandbox");
})->with(['original', 'download', 'small']);

// ============================================================================
// Refusing Other Files
// ============================================================================

it('should not serve a stored file whose contents are not an image, whatever its extension', function (string $template, string $name, string $contents) {
    File::put(storage_path('app/public/imagecache-responses/'.$name), $contents);

    get('cache/'.$template.'/imagecache-responses/'.$name)
        ->assertNotFound();
})->with(['original', 'download', 'small'])->with([
    'markup named as a photo' => ['page.jpg', '<!DOCTYPE html><html><body>hello</body></html>'],
    'vector image named as a picture' => ['drawing.png', '<svg xmlns="http://www.w3.org/2000/svg"></svg>'],
    'markup the sniffer reads as text' => ['notes.txt', '<body>hello</body>'],
]);
