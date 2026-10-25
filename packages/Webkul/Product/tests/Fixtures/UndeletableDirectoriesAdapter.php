<?php

namespace Webkul\Product\Tests\Fixtures;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnableToDeleteDirectory;

class UndeletableDirectoriesAdapter extends LocalFilesystemAdapter
{
    /**
     * Refuse to delete any folder, as a disk does whose files another user owns or whose key cannot delete.
     */
    public function deleteDirectory(string $path): void
    {
        throw UnableToDeleteDirectory::atLocation($path, 'Permission denied.');
    }
}
