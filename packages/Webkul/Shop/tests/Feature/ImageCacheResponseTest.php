<?php

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Image\ImageException;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperationFailed;
use League\Flysystem\UnableToCheckFileExistence;
use League\Flysystem\UnableToReadFile;
use Mockery\MockInterface;
use Webkul\Core\Core as BaseCore;
use Webkul\Core\Facades\Core;
use Webkul\Core\Models\Channel;
use Webkul\ImageCache\Exceptions\UnprocessableImage;
use Webkul\ImageCache\Exceptions\UnreadableImage;
use Webkul\ImageCache\Http\Controllers\ImageCacheController;
use Webkul\Shop\Tests\Fixtures\ImageCache\UnreachableBucketAdapter;
use Webkul\Shop\Tests\Fixtures\ImageCache\UnreadableFilesAdapter;
use Webkul\Theme\Facades\Themes;
use Webkul\Theme\Themes as BaseThemes;

use function Pest\Laravel\get;

/**
 * A png cut short, as a broken upload leaves it: it still reads as a png but cannot be decoded.
 */
function truncatedPng(): string
{
    return substr(UploadedFile::fake()->image('broken.png', 20, 10)->getContent(), 0, 60);
}

/**
 * A gif cut short after its header: it still reads as a gif but cannot be decoded.
 */
function truncatedGif(): string
{
    return substr(UploadedFile::fake()->image('broken.gif', 20, 10)->getContent(), 0, 20);
}

/**
 * A Windows icon holding a small png: the cache serves it as an original, but no template can process it.
 */
function windowsIcon(): string
{
    $png = UploadedFile::fake()->image('icon.png', 1, 1)->getContent();

    return pack('vvv', 0, 1, 1).pack('CCCCvvVV', 1, 1, 0, 0, 1, 32, strlen($png), 22).$png;
}

/**
 * Create a channel on its own host, running a storefront theme copied from the default one under the code.
 */
function channelRunningTheme(string $code): Channel
{
    config(['themes.shop.'.$code => array_merge(config('themes.shop.default'), [
        'name' => ucfirst($code),
    ])]);

    return Channel::factory()->create([
        'theme' => $code,
        'hostname' => 'http://'.$code.'.test',
    ]);
}

/**
 * Request a path on a channel's host, resolving that host's channel afresh.
 */
function getOnChannel(Channel $channel, string $path): TestResponse
{
    Core::clearResolvedInstance(BaseCore::class);

    return get($channel->hostname.'/'.$path);
}

/**
 * The details of each broken image reported so far, in the order they were reported.
 */
function reportedUnprocessableImages(): array
{
    return collect(Exceptions::reported())
        ->whereInstanceOf(UnprocessableImage::class)
        ->map(fn (UnprocessableImage $exception) => $exception->context())
        ->values()
        ->all();
}

/**
 * Replace the default disk with a testing disk read through the given adapter, rooted outside the paths the image
 * cache falls back to, so that only the disk can answer.
 */
function defaultDiskOn(string $adapterClass): void
{
    $adapter = new $adapterClass(Storage::fake()->path(''));

    Storage::set(config('filesystems.default'), new FilesystemAdapter(new Filesystem($adapter), $adapter));
}

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

it('should serve a stored Windows icon as an original icon', function () {
    File::put(storage_path('app/public/imagecache-responses/icon.ico'), windowsIcon());

    $response = get('cache/original/imagecache-responses/icon.ico')
        ->assertOk();

    expect($response->headers->get('Content-Type'))->toBeIn(['image/vnd.microsoft.icon', 'image/x-icon']);
});

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

// ============================================================================
// Channel Theme
// ============================================================================

it('should render the error page of a missing image with the channel theme', function () {
    $channel = channelRunningTheme('store-theme');

    themes()->loadThemes();

    getOnChannel($channel, 'cache/large/imagecache-responses/missing.png')
        ->assertNotFound();

    expect(themes()->current()?->code)->toBe('store-theme');
});

it('should render the error page of a missing image whose path holds the admin segment with the channel theme', function () {
    config(['app.debug' => false]);

    $channel = channelRunningTheme('store-theme');

    Themes::clearResolvedInstance(BaseThemes::class);

    $response = getOnChannel($channel, 'cache/large/imagecache-responses/'.trim(config('app.admin_url'), '/').'/missing.png')
        ->assertNotFound();

    expect(themes()->current()?->code)->toBe('store-theme')
        ->and($response->getContent())->toContain(trans('shop::app.errors.go-to-home'))
        ->and($response->getContent())->not->toContain('themes/admin/');
});

it('should leave the channel theme alone for an image it finds', function () {
    $channel = channelRunningTheme('store-theme');

    themes()->loadThemes();

    File::put(storage_path('app/public/imagecache-responses/source.png'), UploadedFile::fake()->image('source.png', 20, 10)->getContent());

    getOnChannel($channel, 'cache/large/imagecache-responses/source.png')
        ->assertOk();

    expect(themes()->current()?->code)->not->toBe('store-theme');
});

// ============================================================================
// Reporting Faults
// ============================================================================

it('should report a stored image it cannot process, naming the image, the template and the theme', function () {
    Exceptions::fake();

    $channel = channelRunningTheme('store-theme');

    File::put(storage_path('app/public/imagecache-responses/broken.png'), truncatedPng());

    getOnChannel($channel, 'cache/small/imagecache-responses/broken.png')
        ->assertNotFound();

    Exceptions::assertReported(fn (UnprocessableImage $exception) => str_starts_with($exception->getMessage(), 'Image [imagecache-responses/broken.png] could not be processed through template [small]: '));

    Exceptions::assertReported(fn (UnprocessableImage $exception) => $exception->context() === [
        'template' => 'small',
        'filename' => 'imagecache-responses/broken.png',
        'theme' => 'store-theme',
        'mime' => 'image/png',
    ]);
});

it('should report a broken image cut for the theme its url names under that theme', function () {
    Exceptions::fake();

    $channel = channelRunningTheme('store-theme');

    File::put(storage_path('app/public/imagecache-responses/broken.png'), truncatedPng());

    getOnChannel($channel, 'cache/small@default/imagecache-responses/broken.png')
        ->assertNotFound();

    Exceptions::assertReported(fn (UnprocessableImage $exception) => $exception->context() === [
        'template' => 'small',
        'filename' => 'imagecache-responses/broken.png',
        'theme' => 'default',
        'mime' => 'image/png',
    ]);
});

it('should name the kind of failure in the report when the failure carries no message', function () {
    $exception = new UnprocessableImage('small', 'imagecache-responses/broken.png', 'default', 'image/png', new Error);

    expect($exception->getMessage())->toBe('Image [imagecache-responses/broken.png] could not be processed through template [small]: Error.');
});

it('should throttle failures of different kinds apart, though they share the template, theme and type', function () {
    $decoding = new UnprocessableImage('small', 'imagecache-responses/broken.png', 'default', 'image/png', new ImageException('Unable to decode.'));

    $filtering = new UnprocessableImage('small', 'imagecache-responses/broken.png', 'default', 'image/png', new Error('The filter could not run.'));

    expect($decoding->throttleKey())->not->toBe($filtering->throttleKey());
});

it('should report a broken image once, however often it is requested', function () {
    Exceptions::fake();

    File::put(storage_path('app/public/imagecache-responses/broken.png'), truncatedPng());

    foreach (range(1, 3) as $attempt) {
        get('cache/small/imagecache-responses/broken.png')
            ->assertNotFound();
    }

    expect(reportedUnprocessableImages())->toHaveCount(1);
});

it('should report a broken image once, however its path is spelled', function () {
    Exceptions::fake();

    File::put(storage_path('app/public/imagecache-responses/broken.png'), truncatedPng());

    foreach (['imagecache-responses/broken.png', './imagecache-responses/broken.png', 'imagecache-responses//broken.png'] as $path) {
        get('cache/small/'.$path)
            ->assertNotFound();
    }

    expect(reportedUnprocessableImages())->toHaveCount(1);
});

it('should report a broken image again through another template, as another type or on another theme', function () {
    Exceptions::fake();

    $first = channelRunningTheme('first-theme');

    $second = channelRunningTheme('second-theme');

    File::put(storage_path('app/public/imagecache-responses/broken.png'), truncatedPng());

    File::put(storage_path('app/public/imagecache-responses/broken.gif'), truncatedGif());

    foreach ([
        [$first, 'small/imagecache-responses/broken.png'],
        [$first, 'medium/imagecache-responses/broken.png'],
        [$first, 'small/imagecache-responses/broken.gif'],
        [$second, 'small/imagecache-responses/broken.png'],
        [$first, 'small/imagecache-responses/broken.png'],
    ] as [$channel, $path]) {
        getOnChannel($channel, 'cache/'.$path)
            ->assertNotFound();
    }

    expect(reportedUnprocessableImages())->toBe([
        ['template' => 'small', 'filename' => 'imagecache-responses/broken.png', 'theme' => 'first-theme', 'mime' => 'image/png'],
        ['template' => 'medium', 'filename' => 'imagecache-responses/broken.png', 'theme' => 'first-theme', 'mime' => 'image/png'],
        ['template' => 'small', 'filename' => 'imagecache-responses/broken.gif', 'theme' => 'first-theme', 'mime' => 'image/gif'],
        ['template' => 'small', 'filename' => 'imagecache-responses/broken.png', 'theme' => 'second-theme', 'mime' => 'image/png'],
    ]);
});

it('should answer a request anyone may make for something that is not a broken image as not found, reporting nothing', function (string $path) {
    Exceptions::fake();

    File::put(storage_path('app/public/imagecache-responses/notes.png'), 'Plain text, named as a picture.');

    File::put(storage_path('app/public/imagecache-responses/icon.ico'), windowsIcon());

    get('cache/'.$path)
        ->assertNotFound();

    Exceptions::assertNothingReported();
})->with([
    'missing image' => ['small/imagecache-responses/missing.png'],
    'text named as a picture' => ['small/imagecache-responses/notes.png'],
    'icon through a template' => ['small/imagecache-responses/icon.ico'],
    'parent directory' => ['small/..'],
    'encoded parent directory' => ['original/%2e%2e'],
]);

it('should answer a directory published beside the application as not found, reporting nothing', function () {
    Exceptions::fake();

    config(['imagecache.paths' => [storage_path('app/public/imagecache-responses/published')]]);

    File::ensureDirectoryExists(storage_path('app/public/imagecache-responses/published/folder'));

    get('cache/original/folder')
        ->assertNotFound();

    Exceptions::assertNothingReported();
});

// ============================================================================
// Unreadable Disks
// ============================================================================

it('should report an image the disk holds but cannot read, answering it as not found', function () {
    Exceptions::fake();

    defaultDiskOn(UnreadableFilesAdapter::class);

    Storage::put('imagecache-responses/held.png', UploadedFile::fake()->image('held.png', 20, 10)->getContent());

    get('cache/original/imagecache-responses/held.png')
        ->assertNotFound();

    Exceptions::assertReported(fn (UnreadableImage $exception) => $exception->getPrevious() instanceof UnableToReadFile);

    Exceptions::assertReported(fn (UnreadableImage $exception) => $exception->context() === [
        'disk' => config('filesystems.default'),
        'path' => 'imagecache-responses/held.png',
        'operation' => FilesystemOperationFailed::OPERATION_READ,
    ]);
});

it('should report a disk it cannot reach, answering the image as not found', function () {
    Exceptions::fake();

    defaultDiskOn(UnreachableBucketAdapter::class);

    get('cache/small/imagecache-responses/source.png')
        ->assertNotFound();

    Exceptions::assertReported(fn (UnreadableImage $exception) => $exception->getPrevious() instanceof UnableToCheckFileExistence);

    Exceptions::assertReported(fn (UnreadableImage $exception) => $exception->context() === [
        'disk' => config('filesystems.default'),
        'path' => 'imagecache-responses/source.png',
        'operation' => FilesystemOperationFailed::OPERATION_FILE_EXISTS,
    ]);
});

it('should report a disk it cannot reach once, however many images are requested', function () {
    Exceptions::fake();

    defaultDiskOn(UnreachableBucketAdapter::class);

    foreach (['original', 'download', 'small'] as $index => $template) {
        get('cache/'.$template.'/imagecache-responses/image-'.$index.'.png')
            ->assertNotFound();
    }

    expect(collect(Exceptions::reported())->whereInstanceOf(UnreadableImage::class))->toHaveCount(1);
});

it('should answer an image missing from a disk that throws its failures as not found, reporting nothing', function () {
    Exceptions::fake();

    Storage::fake(config: ['throw' => true]);

    get('cache/original/imagecache-responses/missing.png')
        ->assertNotFound();

    Exceptions::assertNothingReported();
});

// ============================================================================
// Bagisto Logo
// ============================================================================

it('should not fetch the Bagisto logo again until ten minutes after a fetch fails', function () {
    $fetches = 0;

    $this->partialMock(ImageCacheController::class, function (MockInterface $mock) use (&$fetches) {
        $mock->shouldAllowMockingProtectedMethods()
            ->shouldReceive('fetchFromUrl')
            ->andReturnUsing(function () use (&$fetches) {
                $fetches++;

                throw new Exception('The update server could not be reached.');
            });
    });

    get('cache/logo/bagisto.png')
        ->assertNotFound();

    $this->travel(9)->minutes();

    get('cache/logo/bagisto.png')
        ->assertNotFound();

    expect($fetches)->toBe(1);

    $this->travel(1)->minutes();

    get('cache/logo/bagisto.png')
        ->assertNotFound();

    expect($fetches)->toBe(2);
});
