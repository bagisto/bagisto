<?php

namespace Webkul\Shop\Http\Controllers\API;

use Webkul\Shop\Helpers\CatalogApiCache;
use Webkul\Shop\Http\Controllers\Controller;

class APIController extends Controller
{
    /**
     * Build `Cache-Control` headers for catalog API responses.
     *
     * Guests receive a publicly cacheable response (so Varnish/CDN can store
     * it); logged-in customers receive personalised, non-cacheable data.
     */
    protected function catalogCacheHeaders(): array
    {
        return app(CatalogApiCache::class)->shouldCache()
            ? ['Cache-Control' => 'public, max-age=60']
            : ['Cache-Control' => 'private, no-cache'];
    }
}
