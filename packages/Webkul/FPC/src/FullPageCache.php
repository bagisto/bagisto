<?php

namespace Webkul\FPC;

use Illuminate\Http\Request;
use Spatie\ResponseCache\ResponseCache;

class FullPageCache
{
    /**
     * Whether this response will be stored and served to everyone, which is what decides
     * if a view may write the visitor's own state or has to leave a marker for a replacer.
     */
    public static function willCache(?Request $request = null): bool
    {
        $request ??= request();

        $route = $request->route();

        if (! $route) {
            return false;
        }

        return in_array('cache.response', $route->gatherMiddleware())
            && app(ResponseCache::class)->enabled($request);
    }
}
