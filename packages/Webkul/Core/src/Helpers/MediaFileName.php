<?php

namespace Webkul\Core\Helpers;

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
     * Build a unique storage path for the given directory, out of a user supplied name.
     *
     * The extension is always dictated by the caller, never by the supplied name, so a
     * name can never change the type of the stored file.
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
     * Rename the file living at the given path.
     *
     * Returns the path the file can be found at afterwards. When the desired name is
     * empty, already in use by this very file, or the move fails, the current path is
     * returned so that the database is never left pointing at a file that is not there.
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
     * Reduce a user supplied name to a safe, slugged base name.
     *
     * Any directory component is dropped before slugging, so a name can never escape
     * the directory it is meant to live in.
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
