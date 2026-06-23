<?php

namespace Webkul\Shop\Listeners;

use Webkul\Shop\Helpers\CatalogApiCache;

class CatalogCache
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected CatalogApiCache $catalogApiCache) {}

    /**
     * Invalidate every cached catalog API response.
     *
     * Triggered whenever a product or category is created, updated or deleted
     * so the storefront serves fresh data immediately.
     *
     * @param  mixed  $entity
     * @return void
     */
    public function flush($entity = null)
    {
        $this->catalogApiCache->flush();
    }
}
