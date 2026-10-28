<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnableToReadFile;

class UnreadableFilesAdapter extends LocalFilesystemAdapter
{
    /**
     * Refuse every read, as a bucket does for an object it holds but will not hand over.
     */
    public function read(string $path): string
    {
        throw UnableToReadFile::fromLocation($path, 'Access Denied.');
    }
}
