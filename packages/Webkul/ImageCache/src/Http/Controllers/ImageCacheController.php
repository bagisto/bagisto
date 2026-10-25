<?php

namespace Webkul\ImageCache\Http\Controllers;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemOperationFailed;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\PathTraversalDetected;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Webkul\Core\Helpers\InstalledPackages;
use Webkul\ImageCache\Exceptions\UnprocessableImage;
use Webkul\ImageCache\Exceptions\UnreadableImage;
use Webkul\ImageCache\TemplateRegistry;
use Webkul\Shop\Http\Middleware\Theme;
use Webkul\User\Repositories\AdminRepository;

class ImageCacheController extends Controller
{
    /**
     * The Bagisto logo URL.
     */
    protected const BAGISTO_LOGO = 'https://updates.bagisto.com/bagisto.png';

    /**
     * The cache key remembering that the Bagisto logo could not be fetched.
     */
    protected const LOGO_UNREACHABLE_KEY = 'imagecache.logo-unreachable';

    /**
     * How long a failed fetch of the Bagisto logo is remembered, so a store that cannot reach the update
     * server does not try again on every admin page.
     */
    protected const LOGO_RETRY_MINUTES = 10;

    /**
     * Seconds a remote fetch may wait, so an unreachable host cannot hold a worker.
     */
    protected const FETCH_TIMEOUT = 3;

    /**
     * The image types the cache serves, so a stored file whose bytes read as anything else is never sent from the store.
     */
    protected const SERVABLE_MIME_TYPES = [
        'image/avif', 'image/bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/vnd.microsoft.icon', 'image/webp',
        'image/x-icon', 'image/x-ms-bmp',
    ];

    /**
     * The servable types the image driver can process through a template, so a request for any other type is
     * never reported as a broken image.
     */
    protected const PROCESSABLE_MIME_TYPES = [
        'image/avif', 'image/bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/webp',
    ];

    /**
     * The current cache template name.
     */
    protected string $template = '';

    /**
     * Create a new controller instance.
     */
    public function __construct(protected TemplateRegistry $templateRegistry) {}

    /**
     * Get the HTTP response for the requested image, applying the channel theme only when it is not found.
     */
    public function getResponse(string $template, string $filename): Response
    {
        $this->template = $template;

        try {
            return match (strtolower($template)) {
                'original' => $this->getOriginal($filename),
                'download' => $this->getDownload($filename),
                'logo' => $this->getLogo(),
                default => $this->getImage($template, $filename),
            };
        } catch (NotFoundHttpException $exception) {
            $this->applyChannelTheme();

            throw $exception;
        }
    }

    /**
     * Get the HTTP response for an image cut by a template, of the theme its url names or else the requesting
     * channel's.
     */
    protected function getImage(string $template, string $filename): Response
    {
        [$name, $themeCode] = $this->templateRegistry->resolve($template);

        $class = $this->templateRegistry->find($name, $themeCode);

        if (! $class) {
            abort(404, 'Template not found.');
        }

        $contents = $this->getImageContents($filename);

        if (is_null($contents)) {
            abort(404, 'Image not found.');
        }

        try {
            $content = $this->applyTemplate($class, $contents);
        } catch (Throwable $exception) {
            $this->reportUnprocessable($name, $themeCode, $filename, $contents, $exception);

            abort(404, 'Unable to process image.');
        }

        return $this->buildResponse($content);
    }

    /**
     * Run a stored image through the filter the container builds for a template class, returning the bytes of
     * the image it produces.
     */
    protected function applyTemplate(string $class, string $contents): string
    {
        $filter = app($class);

        return $filter->applyFilter(image_manager()->fromBytes($contents))->toBytes();
    }

    /**
     * Report a stored image of a type the driver processes that still failed, leaving any other file, which
     * anyone may request, and a filter's deliberate http response unreported.
     */
    protected function reportUnprocessable(string $template, ?string $themeCode, string $filename, string $contents, Throwable $exception): void
    {
        if (
            $exception instanceof HttpExceptionInterface
            || $exception instanceof HttpResponseException
        ) {
            return;
        }

        $mime = $this->detectMime($contents);

        if (! in_array($mime, self::PROCESSABLE_MIME_TYPES)) {
            return;
        }

        report(new UnprocessableImage($template, $filename, $themeCode, $mime, $exception));
    }

    /**
     * Get the logo image from a remote URL, remembering a failed fetch for a while so it is not retried on
     * every admin page.
     */
    protected function getLogo(): Response
    {
        if (Cache::has(self::LOGO_UNREACHABLE_KEY)) {
            abort(404, 'Unable to fetch logo.');
        }

        try {
            $content = $this->fetchFromUrl($this->getLogoUrl());

            return $this->buildResponse($content);
        } catch (Exception) {
            Cache::put(self::LOGO_UNREACHABLE_KEY, true, now()->addMinutes(self::LOGO_RETRY_MINUTES));

            abort(404, 'Unable to fetch logo.');
        }
    }

    /**
     * Build the logo URL, appending the packages this installation is running so
     * the tracker can record what its live instances are made up of.
     */
    protected function getLogoUrl(): string
    {
        $url = self::BAGISTO_LOGO;

        $packages = app(InstalledPackages::class)->all();

        if (! empty($packages)) {
            $url .= (str_contains($url, '?') ? '&' : '?').http_build_query(['modules' => $packages]);
        }

        return $url;
    }

    /**
     * Fetch image content from a URL.
     *
     * @throws Exception
     */
    protected function fetchFromUrl(string $url): string
    {
        $domain = config('app.url');

        $options = [
            'http' => [
                'method' => 'GET',
                'protocol_version' => 1.1,
                'timeout' => self::FETCH_TIMEOUT,
                'header' => "Accept-language: en\r\n".
                    "Domain: $domain\r\n".
                    $this->getAdminHeaders().
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36\r\n",
            ],
        ];

        $context = stream_context_create($options);

        $data = @file_get_contents($url, false, $context);

        if ($data === false) {
            throw new Exception('Unable to fetch from URL: '.$url);
        }

        return $data;
    }

    /**
     * Header lines naming the installer's admin, the lowest id, url encoded so that no
     * name can break the header block.
     */
    protected function getAdminHeaders(): string
    {
        $admin = app(AdminRepository::class)->orderBy('id')->first();

        if (! $admin) {
            return '';
        }

        return 'Admin-Name: '.rawurlencode((string) $admin->name)."\r\n".
            'Admin-Email: '.rawurlencode((string) $admin->email)."\r\n";
    }

    /**
     * Get the original image without any transformations.
     */
    protected function getOriginal(string $filename): Response
    {
        $content = $this->getImageContents($filename);

        if (is_null($content)) {
            abort(404, 'Image not found.');
        }

        return $this->buildResponse($content);
    }

    /**
     * Get the image as a download.
     */
    protected function getDownload(string $filename): Response
    {
        $content = $this->getImageContents($filename);

        if (is_null($content)) {
            abort(404, 'Image not found.');
        }

        $response = $this->buildResponse($content);

        $response->header('Content-Disposition', 'attachment; filename="'.basename($filename).'"');

        return $response;
    }

    /**
     * Read an image from the configured disk, then from the configured paths for a file published beside
     * the application rather than held on a disk.
     */
    protected function getImageContents(string $filename): ?string
    {
        $contents = $this->readFromDisk($this->sanitizeFilename($filename));

        if (! is_null($contents)) {
            return $contents;
        }

        $path = $this->getImagePath($filename);

        if (! is_file($path)) {
            return null;
        }

        $contents = @file_get_contents($path);

        return $contents === false ? null : $contents;
    }

    /**
     * Read a file through the default disk's driver, whatever the disk's throw and report settings, answering
     * null for a file it cannot read.
     */
    protected function readFromDisk(string $path): ?string
    {
        $driver = Storage::getDriver();

        try {
            return $driver->read($path);
        } catch (PathTraversalDetected) {
            return null;
        } catch (FilesystemOperationFailed $exception) {
            $this->reportUnreadable($driver, $path, $exception);

            return null;
        }
    }

    /**
     * Report a failed read unless the disk says the file is simply not there, and report the disk itself when
     * it cannot say.
     */
    protected function reportUnreadable(FilesystemOperator $driver, string $path, FilesystemOperationFailed $exception): void
    {
        try {
            $failure = $driver->fileExists($path) ? $exception : null;
        } catch (FilesystemOperationFailed $checkFailure) {
            $failure = $checkFailure;
        }

        if ($failure) {
            report(new UnreadableImage(config('filesystems.default'), $path, $failure));
        }
    }

    /**
     * Get the full image path from the filename.
     */
    protected function getImagePath(string $filename): string
    {
        $filename = $this->sanitizeFilename($filename);

        $paths = config('imagecache.paths', []);

        foreach ($paths as $basePath) {
            $basePath = realpath(rtrim($basePath, '/'));

            if (! $basePath) {
                continue;
            }

            $realPath = realpath($basePath.'/'.$filename);

            if (
                $realPath
                && str_starts_with($realPath, $basePath.'/')
            ) {
                return $realPath;
            }
        }

        $storageBase = realpath(storage_path('app/public'));

        if ($storageBase) {
            $realPath = realpath($storageBase.'/'.$filename);

            if (
                $realPath
                && str_starts_with($realPath, $storageBase.'/')
            ) {
                return $realPath;
            }
        }

        $publicBase = realpath(public_path());

        if ($publicBase) {
            $realPath = realpath($publicBase.'/'.$filename);

            if (
                $realPath
                && str_starts_with($realPath, $publicBase.'/')
            ) {
                return $realPath;
            }
        }

        $storagePublicBase = realpath(public_path('storage'));

        if ($storagePublicBase) {
            $realPath = realpath($storagePublicBase.'/'.$filename);

            if (
                $realPath
                && str_starts_with($realPath, $storagePublicBase.'/')
            ) {
                return $realPath;
            }
        }

        return storage_path('app/public/'.$filename);
    }

    /**
     * Sanitize the filename to prevent path traversal.
     */
    protected function sanitizeFilename(string $filename): string
    {
        do {
            $sanitized = str_replace(['../', '..\\', '/..', '\\..'], '', $filename);

            if ($sanitized === $filename) {
                break;
            }

            $filename = $sanitized;
        } while (true);

        return ltrim($filename, '/\\');
    }

    /**
     * The media type the bytes read as, whatever the file is named.
     */
    protected function detectMime(string $content): string
    {
        return (string) finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);
    }

    /**
     * Build the HTTP response with the image content, refusing content that is not a servable image.
     */
    protected function buildResponse(string $content): Response
    {
        $mime = $this->detectMime($content);

        if (! in_array($mime, self::SERVABLE_MIME_TYPES)) {
            abort(404, 'Image not found.');
        }

        $eTag = md5($content);

        $notModified = request()->header('If-None-Match') === $eTag;

        $statusCode = $notModified ? 304 : 200;

        $responseContent = $notModified ? null : $content;

        $maxAge = ($this->template === 'logo' ? 10080 : config('imagecache.lifetime', 43200)) * 60;

        return new Response($responseContent, $statusCode, [
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age='.$maxAge.', public',
            'Content-Length' => strlen($content),
            'Content-Security-Policy' => "default-src 'none'; style-src 'unsafe-inline'; sandbox",
            'Etag' => $eTag,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Apply the current channel's shop theme for the 404 page, which the image route, registered outside the shop
     * middleware group, never applies itself.
     */
    protected function applyChannelTheme(): void
    {
        app(Theme::class)->handle(request(), fn () => null);
    }
}
