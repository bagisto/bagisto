<?php

namespace Webkul\Core\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFileName
{
    /**
     * Maximum length allowed for the sanitized file name, without its extension.
     */
    public const MAX_LENGTH = 150;

    /**
     * Maximum number of suffixes tried while resolving a name collision.
     */
    public const MAX_COLLISION_ATTEMPTS = 100;

    /**
     * Extensions an uploaded media file may be stored under, none of which a web server runs or renders as a page.
     */
    public const ALLOWED_EXTENSIONS = ['avif', 'bmp', 'gif', 'ico', 'jpeg', 'jpg', 'mov', 'mp4', 'ogg', 'ogv', 'png', 'webm', 'webp'];

    /**
     * Extension given to an uploaded file whose type is none of the allowed ones.
     */
    public const FALLBACK_EXTENSION = 'bin';

    /**
     * Build a unique storage path in the given directory out of a user supplied name, under the caller's
     * extension, so a name can never change the type of the stored file.
     */
    public function resolve(string $directory, ?string $desiredName, string $extension): string
    {
        $baseName = $this->sanitize($desiredName);

        $directory = rtrim($directory, '/');

        $extension = ltrim($extension, '.');

        $path = $directory.'/'.$baseName.'.'.$extension;

        if (! Storage::exists($path)) {
            return $path;
        }

        for ($suffix = 1; $suffix <= self::MAX_COLLISION_ATTEMPTS; $suffix++) {
            $path = $directory.'/'.$baseName.'-'.$suffix.'.'.$extension;

            if (! Storage::exists($path)) {
                return $path;
            }
        }

        return $directory.'/'.Str::random(40).'.'.$extension;
    }

    /**
     * Get the extension to store an uploaded file under, preferring the one detected from its contents
     * and never one outside the allowed extensions.
     */
    public function extension(UploadedFile $file): string
    {
        foreach ([$file->guessExtension(), $file->getClientOriginalExtension()] as $extension) {
            $extension = strtolower((string) $extension);

            if (in_array($extension, self::ALLOWED_EXTENSIONS)) {
                return $extension;
            }
        }

        return self::FALLBACK_EXTENSION;
    }

    /**
     * Rename the file at the given path and return where it lives afterwards, which stays the current
     * path when the name is empty or unchanged, or the move fails.
     */
    public function rename(string $currentPath, ?string $desiredName): string
    {
        if (blank($desiredName)) {
            return $currentPath;
        }

        $extension = pathinfo($currentPath, PATHINFO_EXTENSION);

        $targetName = $this->sanitize($desiredName).($extension ? '.'.$extension : '');

        if ($targetName === basename($currentPath)) {
            return $currentPath;
        }

        if (! Storage::exists($currentPath)) {
            return $currentPath;
        }

        $newPath = $this->resolve(dirname($currentPath), $desiredName, $extension);

        try {
            Storage::move($currentPath, $newPath);
        } catch (\Throwable $exception) {
            Log::error('Unable to rename media file "'.$currentPath.'" to "'.$newPath.'": '.$exception->getMessage());

            return $currentPath;
        }

        return $newPath;
    }

    /**
     * Reduce a user supplied name to a safe, slugged base name, dropping any directory component first
     * so a name can never escape the directory it is meant to live in.
     */
    public function sanitize(?string $desiredName): string
    {
        $baseName = pathinfo((string) $desiredName, PATHINFO_FILENAME);

        $baseName = Str::slug($baseName);

        if ($baseName === '') {
            return Str::random(40);
        }

        return Str::limit($baseName, self::MAX_LENGTH, '');
    }
}
