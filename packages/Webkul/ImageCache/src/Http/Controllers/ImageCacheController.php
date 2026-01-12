<?php

namespace Webkul\ImageCache\Http\Controllers;

use Closure;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ImageCacheController extends Controller
{
    /**
     * The current cache template name.
     */
    protected string $template = '';

    /**
     * The Bagisto logo URL.
     */
    protected const BAGISTO_LOGO = 'https://updates.bagisto.com/bagisto.png';

    /**
     * Get the HTTP response for the requested image.
     */
    public function getResponse(string $template, string $filename): Response
    {
        $this->template = $template;

        return match (strtolower($template)) {
            'original' => $this->getOriginal($filename),
            'download' => $this->getDownload($filename),
            'logo'     => $this->getLogo(),
            default    => $this->getImage($template, $filename),
        };
    }

    /**
     * Get the HTTP response for a template-processed image.
     */
    protected function getImage(string $template, string $filename): Response
    {
        $templateConfig = $this->getTemplate($template);

        if (! $templateConfig) {
            abort(404, 'Template not found.');
        }

        $path = $this->getImagePath($filename);

        if (! file_exists($path)) {
            abort(404, 'Image not found.');
        }

        try {
            $manager = app('image');

            $image = $manager->read($path);

            if (is_object($templateConfig) && method_exists($templateConfig, 'applyFilter')) {
                $image = $templateConfig->applyFilter($image);
            } elseif (class_exists($templateConfig)) {
                $filter = new $templateConfig;

                if (method_exists($filter, 'applyFilter')) {
                    $image = $filter->applyFilter($image);
                }
            } elseif ($templateConfig instanceof Closure) {
                $image = $templateConfig($image);
            }

            $content = (string) $image->encodeByMediaType();

            return $this->buildResponse($content);
        } catch (Exception) {
            abort(404, 'Unable to process image.');
        }
    }

    /**
     * Get the logo image from a remote URL.
     */
    protected function getLogo(): Response
    {
        try {
            $content = $this->fetchFromUrl(self::BAGISTO_LOGO);

            return $this->buildResponse($content);
        } catch (Exception) {
            abort(404, 'Unable to fetch logo.');
        }
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
                'method'           => 'GET',
                'protocol_version' => 1.1,
                'header'           => "Accept-language: en\r\n".
                    "Domain: $domain\r\n".
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
     * Get the original image without any transformations.
     */
    protected function getOriginal(string $filename): Response
    {
        $path = $this->getImagePath($filename);

        if (! file_exists($path)) {
            abort(404, 'Image not found.');
        }

        $content = file_get_contents($path);

        return $this->buildResponse($content);
    }

    /**
     * Get the image as a download.
     */
    protected function getDownload(string $filename): Response
    {
        $path = $this->getImagePath($filename);

        if (! file_exists($path)) {
            abort(404, 'Image not found.');
        }

        $content = file_get_contents($path);

        $response = $this->buildResponse($content);

        $response->header('Content-Disposition', 'attachment; filename="'.basename($filename).'"');

        return $response;
    }

    /**
     * Get the full image path from the filename.
     */
    protected function getImagePath(string $filename): string
    {
        $paths = config('imagecache.paths', []);

        foreach ($paths as $basePath) {
            $fullPath = rtrim($basePath, '/').'/'.ltrim($filename, '/');

            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }

        $storagePath = storage_path('app/public/'.$filename);

        if (file_exists($storagePath)) {
            return $storagePath;
        }

        $publicPath = public_path($filename);

        if (file_exists($publicPath)) {
            return $publicPath;
        }

        $storagePublicPath = public_path('storage/'.$filename);

        if (file_exists($storagePublicPath)) {
            return $storagePublicPath;
        }

        return $storagePath;
    }

    /**
     * Get the template class or closure.
     */
    protected function getTemplate(string $template): mixed
    {
        $templates = config('imagecache.templates', []);

        return $templates[$template] ?? null;
    }

    /**
     * Build the HTTP response with the image content.
     */
    protected function buildResponse(string $content): Response
    {
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

        $eTag = md5($content);

        $notModified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $eTag;

        $statusCode = $notModified ? 304 : 200;

        $responseContent = $notModified ? null : $content;

        $maxAge = ($this->template === 'logo' ? 10080 : config('imagecache.lifetime', 43200)) * 60;

        return new Response($responseContent, $statusCode, [
            'Content-Type'   => $mime,
            'Cache-Control'  => 'max-age='.$maxAge.', public',
            'Content-Length' => strlen($content),
            'Etag'           => $eTag,
        ]);
    }
}
