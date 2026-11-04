<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Webkul\Core\Helpers\MediaFileName;
use Webkul\Core\Helpers\MediaUpload;

beforeEach(function () {
    $this->mediaUpload = app(MediaUpload::class);
});

/**
 * Whether the file passes the given media rules.
 */
function passesMediaRules(UploadedFile $file, array $rules): bool
{
    return Validator::make(['file' => $file], ['file' => $rules])->passes();
}

// ============================================================================
// Allowed Extensions
// ============================================================================

it('should allow the default extensions until an admin chooses others', function () {
    expect($this->mediaUpload->extensions(MediaUpload::IMAGE))->toBe(MediaUpload::DEFAULT_EXTENSIONS[MediaUpload::IMAGE])
        ->and($this->mediaUpload->extensions(MediaUpload::VIDEO))->toBe(MediaUpload::DEFAULT_EXTENSIONS[MediaUpload::VIDEO]);
});

it('should allow the extensions an admin configured', function () {
    $this->setConfig('file_management.media.images.allowed_extensions', 'png,gif');

    expect($this->mediaUpload->extensions(MediaUpload::IMAGE))->toBe(['gif', 'png']);
});

it('should drop a configured extension that is not supported', function () {
    $this->setConfig('file_management.media.images.allowed_extensions', 'php,PNG, svg');

    expect($this->mediaUpload->extensions(MediaUpload::IMAGE))->toBe(['png']);
});

it('should fall back to the default extensions when none configured is supported', function () {
    $this->setConfig('file_management.media.videos.allowed_extensions', 'exe');

    expect($this->mediaUpload->extensions(MediaUpload::VIDEO))->toBe(MediaUpload::DEFAULT_EXTENSIONS[MediaUpload::VIDEO]);
});

// ============================================================================
// Maximum Size
// ============================================================================

it('should allow the configured size', function () {
    $this->setConfig('file_management.media.images.max_size', 500);

    expect($this->mediaUpload->maxSize(MediaUpload::IMAGE))->toBe(min(500, $this->mediaUpload->serverMaxSize()));
});

it('should fall back to the default size when the configured one is not positive', function () {
    $this->setConfig('file_management.media.videos.max_size', 0);

    expect($this->mediaUpload->maxSize(MediaUpload::VIDEO))
        ->toBe(min(MediaUpload::DEFAULT_MAX_SIZES[MediaUpload::VIDEO], $this->mediaUpload->serverMaxSize()));
});

it('should never allow more than the server accepts', function () {
    $this->setConfig('file_management.media.videos.max_size', PHP_INT_MAX);

    expect($this->mediaUpload->maxSize(MediaUpload::VIDEO))->toBe($this->mediaUpload->serverMaxSize());
});

it('should format a size the way a person reads it', function () {
    expect($this->mediaUpload->formatSize(512))->toBe('512 KB')
        ->and($this->mediaUpload->formatSize(10240))->toBe('10 MB')
        ->and($this->mediaUpload->formatSize(1536))->toBe('1.5 MB');
});

// ============================================================================
// Validation Rules
// ============================================================================

it('should accept an image of every default extension', function (string $file) {
    expect(passesMediaRules(UploadedFile::fake()->image($file), $this->mediaUpload->imageRules()))->toBeTrue();
})->with(['photo.bmp', 'photo.jpeg', 'photo.jpg', 'photo.png', 'photo.webp']);

it('should refuse an image of an extension that is not allowed', function () {
    expect(passesMediaRules(UploadedFile::fake()->image('photo.gif'), $this->mediaUpload->imageRules()))->toBeFalse();
});

it('should refuse an image whose name claims a script', function () {
    expect(passesMediaRules(UploadedFile::fake()->image('photo.php'), $this->mediaUpload->imageRules()))->toBeFalse();
});

it('should refuse a file whose content is not the image its name claims', function () {
    $path = tempnam(sys_get_temp_dir(), 'media');

    file_put_contents($path, '<?php echo 1;');

    $file = new UploadedFile($path, 'photo.png', null, null, true);

    expect(passesMediaRules($file, $this->mediaUpload->imageRules()))->toBeFalse();

    unlink($path);
});

it('should accept the extra extensions a single field allows', function () {
    $rules = $this->mediaUpload->imageRules(['ico']);

    expect($rules)->toContain('extensions:bmp,jpeg,jpg,png,webp,ico')
        ->and($rules)->toContain('mimes:bmp,jpeg,jpg,png,webp,ico');
});

it('should refuse an image larger than the configured size', function () {
    $this->setConfig('file_management.media.images.max_size', 100);

    expect(passesMediaRules(UploadedFile::fake()->image('photo.png')->size(101), $this->mediaUpload->imageRules()))->toBeFalse()
        ->and(passesMediaRules(UploadedFile::fake()->image('photo.png')->size(99), $this->mediaUpload->imageRules()))->toBeTrue();
});

it('should accept a video of every default extension', function (string $file, string $mime) {
    expect(passesMediaRules(UploadedFile::fake()->create($file, 8, $mime), $this->mediaUpload->videoRules()))->toBeTrue();
})->with([
    ['clip.mp4', 'video/mp4'],
    ['clip.webm', 'video/webm'],
    ['clip.mov', 'video/quicktime'],
]);

it('should refuse a video of a type that is not allowed', function () {
    expect(passesMediaRules(UploadedFile::fake()->create('clip.mkv', 8, 'video/x-matroska'), $this->mediaUpload->videoRules()))->toBeFalse();
});

it('should refuse an arbitrary binary sent as a video', function () {
    expect(passesMediaRules(UploadedFile::fake()->create('clip.mp4', 8, 'application/octet-stream'), $this->mediaUpload->videoRules()))->toBeFalse();
});

it('should refuse a video larger than the configured size', function () {
    $this->setConfig('file_management.media.videos.max_size', 100);

    expect(passesMediaRules(UploadedFile::fake()->create('clip.mp4', 101, 'video/mp4'), $this->mediaUpload->videoRules()))->toBeFalse();
});

it('should limit a file of any type by the file size setting alone', function () {
    $this->setConfig('file_management.media.files.max_size', 100);

    expect($this->mediaUpload->fileRules())->toBe(['file', 'max:'.min(100, $this->mediaUpload->serverMaxSize())])
        ->and(passesMediaRules(UploadedFile::fake()->create('manual.pdf', 99, 'application/pdf'), $this->mediaUpload->fileRules()))->toBeTrue()
        ->and(passesMediaRules(UploadedFile::fake()->create('manual.pdf', 101, 'application/pdf'), $this->mediaUpload->fileRules()))->toBeFalse();
});

// ============================================================================
// Configuration Options
// ============================================================================

it('should offer every supported extension as an option', function () {
    expect(array_column(MediaUpload::extensionOptions(MediaUpload::VIDEO), 'value'))
        ->toBe(MediaUpload::SUPPORTED_EXTENSIONS[MediaUpload::VIDEO]);
});

it('should only offer extensions a stored media file may carry', function (string $type) {
    expect(array_diff(MediaUpload::SUPPORTED_EXTENSIONS[$type], MediaFileName::ALLOWED_EXTENSIONS))->toBeEmpty();
})->with([MediaUpload::IMAGE, MediaUpload::VIDEO]);
