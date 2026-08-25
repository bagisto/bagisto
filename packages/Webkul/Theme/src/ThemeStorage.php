<?php

namespace Webkul\Theme;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Local\LocalFilesystemAdapter;

class ThemeStorage
{
    /**
     * The sizes an image is offered in, largest first.
     */
    public const SIZES = ['large', 'medium', 'small'];

    /**
     * Resolve a stored path to the url it is served from.
     *
     * A local disk is addressed through the site, so an image and its resized
     * copies come from the one origin the page was asked for.
     */
    public function url(?string $path): ?string
    {
        $path = $this->normalize($path);

        if (is_null($path)) {
            return null;
        }

        if ($this->isAbsolute($path)) {
            return $path;
        }

        if (! $this->isDriverLocal()) {
            return Storage::url($path);
        }

        return url($this->pathOnSite($path));
    }

    /**
     * Resolve a stored image to the url for a resized copy.
     */
    public function resizedUrl(?string $path, string $size): ?string
    {
        $path = $this->normalize($path);

        if (is_null($path)) {
            return null;
        }

        if ($this->isAbsolute($path)) {
            return $path;
        }

        return url('cache/'.$size.'/'.$path);
    }

    /**
     * Resolve a stored image to the original url and every resized one.
     *
     * @return array{url: string, srcset: array<string, string>}|null
     */
    public function imageUrls(?string $path): ?array
    {
        $path = $this->normalize($path);

        if (is_null($path)) {
            return null;
        }

        $srcset = [];

        foreach (self::SIZES as $size) {
            $srcset[$size] = $this->resizedUrl($path, $size);
        }

        return [
            'url' => $this->url($path),
            'srcset' => $srcset,
        ];
    }

    /**
     * Resolve a stored path to the url to write into authored markup.
     *
     * Markup keeps whatever url it is given, so a local disk is addressed from the
     * site root rather than with a domain that can later change.
     */
    public function embedUrl(?string $path): ?string
    {
        $path = $this->normalize($path);

        if (is_null($path)) {
            return null;
        }

        if (
            $this->isAbsolute($path)
            || ! $this->isDriverLocal()
        ) {
            return $this->url($path);
        }

        return $this->pathOnSite($path);
    }

    /**
     * The url a stored path is resolved against.
     *
     * Read off a resolved path because a remote disk rejects an empty key.
     */
    public function base(): string
    {
        $sentinel = '__base__';

        $url = (string) $this->url($sentinel);

        return str_ends_with($url, $sentinel)
            ? substr($url, 0, -strlen($sentinel))
            : $url;
    }

    /**
     * Reduce a stored value to the path it names on the disk.
     *
     * The `storage/` prefix used before paths and urls were separated is tolerated.
     */
    public function normalize(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if ($this->isAbsolute($path)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return $path;
    }

    /**
     * Where a stored path is served from, as a path on this site.
     */
    protected function pathOnSite(string $path): string
    {
        $published = parse_url((string) Storage::url($path), PHP_URL_PATH);

        if (
            ! is_string($published)
            || $published === ''
        ) {
            return '/storage/'.$path;
        }

        return $published;
    }

    /**
     * Whether the value is already a url rather than a path on the disk.
     */
    protected function isAbsolute(string $path): bool
    {
        return str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//');
    }

    /**
     * Whether the configured disk is served from the local filesystem.
     */
    protected function isDriverLocal(): bool
    {
        return Storage::getAdapter() instanceof LocalFilesystemAdapter;
    }
}
