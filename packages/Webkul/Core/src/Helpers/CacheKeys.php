<?php

namespace Webkul\Core\Helpers;

use Prettus\Repository\Helpers\CacheKeys as BaseCacheKeys;

class CacheKeys extends BaseCacheKeys
{
    /**
     * Stop tracking a repository's keys, once its cache entries have been forgotten.
     */
    public static function forgetGroup(string $group): void
    {
        self::loadKeys();

        unset(self::$keys[$group]);

        self::storeKeys();
    }
}
