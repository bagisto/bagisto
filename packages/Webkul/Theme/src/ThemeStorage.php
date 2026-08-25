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
     * Resolve a stored path to the url the storefront requests it from.
     *
     * A theme records the path an upload was stored at, never a url, so the same
     * row resolves against whichever disk is configured rather than freezing the
     * domain that was live when the operator uploaded the file. Videos are served
     * from the disk the same way images are.
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
     *
     * Only images are resized — the image cache route reads local files and
     * decodes what it is given — so a remote disk, and any file it cannot
     * resize, is handed the original instead of a url that would fail.
     */
    public function resizedUrl(?string $path, string $size): ?string
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
     * Custom html keeps whatever url it was written with, so the url must not carry
     * a domain that can later change. A local disk is addressed from the site root,
     * which survives the app moving domain; a remote disk is addressed in full,
     * because its host is the bucket's rather than the application's.
     */
    public function embedUrl(?string $path): ?string
    {
        $normalized = $this->normalize($path);

        if (is_null($normalized)) {
            return null;
        }

        if (
            $this->isAbsolute($normalized)
            || ! $this->isDriverLocal()
        ) {
            return $this->url($normalized);
        }

        return $this->pathOnSite($normalized);
    }

    /**
     * The url a stored path is resolved against.
     *
     * The admin editor previews a path it has only just recorded, so it needs the
     * same base the storefront resolves against rather than assuming `/storage/`.
     */
    public function base(): string
    {
        return Storage::url('');
    }

    /**
     * Reduce a stored value to the path it names on the disk.
     *
     * A `storage/` prefixed path was stored before the path and the url were
     * separated, and custom themes may still hand one over, so the prefix is
     * tolerated on read rather than assumed to be gone.
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
     *
     * A local disk is published under the site itself, so everything it serves is
     * addressed from the root. That keeps an image on the host the page was asked
     * for, alongside the resized copies the image cache route answers.
     */
    private function pathOnSite(string $path): string
    {
        $published = parse_url((string) Storage::url($path), PHP_URL_PATH);

        return is_string($published) && $published !== '' ? $published : '/storage/'.$path;
    }

    /**
     * Whether the value is already a url rather than a path on the disk.
     */
    private function isAbsolute(string $path): bool
    {
        return str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//');
    }

    /**
     * Whether the configured disk is served from the local filesystem.
     */
    private function isDriverLocal(): bool
    {
        return Storage::getAdapter() instanceof LocalFilesystemAdapter;
    }
}
