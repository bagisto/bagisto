<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use League\Flysystem\UnableToCheckFileExistence;
use RuntimeException;

class UnreachableBucketAdapter extends UnreadableFilesAdapter
{
    /**
     * Fail to say whether a file is held, as a bucket does whose endpoint cannot be reached.
     */
    public function fileExists(string $location): bool
    {
        throw UnableToCheckFileExistence::forLocation($location, new RuntimeException('Could not resolve host.'));
    }
}
