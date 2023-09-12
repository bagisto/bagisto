<?php

namespace Webkul\FPC\Hasher;

use Illuminate\Http\Request;
use Spatie\ResponseCache\Hasher\DefaultHasher as BaseDefaultHasher;

class DefaultHasher extends BaseDefaultHasher
{
    /**
     * Get the hash for the given request.
     * 
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function getNormalizedRequestUri(Request $request): string
    {
        return $request->getBaseUrl() . $request->getPathInfo();
    }

    /**
     * Get the cache name suffix for the given request.
     * 
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function getCacheNameSuffix(Request $request): string
    {
        if ($request->attributes->has('responsecache.cacheNameSuffix')) {
            return $request->attributes->get('responsecache.cacheNameSuffix');
        }

        $cacheNameSuffix = core()->getCurrentChannel()->code
            . '-' . core()->getCurrentLocale()->code
            . '-' . core()->getCurrentCurrency()->code
            . '-' . $this->cacheProfile->useCacheNameSuffix($request);

        return $cacheNameSuffix;
    }
}