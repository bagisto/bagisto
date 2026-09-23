<?php

namespace Webkul\Core\Helpers;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StoredFile
{
    /**
     * Send a stored file as a download, from the private disk or the default one.
     */
    public function download(string $path, ?string $name = null): StreamedResponse
    {
        return $this->disk($path)->download($path, $name);
    }

    /**
     * Send a stored file for display in the page rather than as a download.
     */
    public function inline(string $path): StreamedResponse
    {
        return $this->disk($path)->response($path);
    }

    /**
     * The disk holding a file, preferring the private one.
     */
    public function disk(string $path): Filesystem
    {
        foreach (['private', config('filesystems.default')] as $name) {
            $disk = Storage::disk($name);

            if ($disk->exists($path)) {
                return $disk;
            }
        }

        abort(404);
    }
}
