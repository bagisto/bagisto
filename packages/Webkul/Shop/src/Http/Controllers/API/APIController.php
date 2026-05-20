<?php

namespace Webkul\Shop\Http\Controllers\API;

use Webkul\Shop\Helpers\CatalogApiCache;
use Webkul\Shop\Http\Controllers\Controller;

class APIController extends Controller
{
    /**
     * Build `Cache-Control` headers for catalog API responses.
     *
     * Guests receive a publicly cacheable response (so nginx/proxy/CDN can
     * store it); logged-in customers receive personalised, non-cacheable
     * data. The guest response also carries `Vary: Cookie` because the same
     * catalog URLs return personalised data per customer session, so a
     * shared cache stores a separate variant per session cookie.
     */
    protected function catalogCacheHeaders(): array
    {
        if (! app(CatalogApiCache::class)->shouldCache()) {
            return ['Cache-Control' => 'no-cache, private'];
        }

        return [
            'Cache-Control' => 'max-age=60, public',
            'Vary' => 'Cookie',
        ];
    }
}
