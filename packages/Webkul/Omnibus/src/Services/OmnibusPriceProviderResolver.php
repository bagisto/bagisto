<?php

namespace Webkul\Omnibus\Services;

use Illuminate\Contracts\Container\Container;
use Webkul\Omnibus\Contracts\OmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\DefaultOmnibusPriceProvider;
use Webkul\Product\Contracts\Product;

class OmnibusPriceProviderResolver
{
    /**
     * Create a new resolver instance.
     */
    public function __construct(
        protected Container $container
    ) {}

    /**
     * Resolve the Omnibus price provider for the given product.
     */
    public function resolve(Product $product): OmnibusPriceProvider
    {
        $class = config("omnibus.providers.types.{$product->type}")
            ?? config('omnibus.providers.default', DefaultOmnibusPriceProvider::class);

        return $this->container->make($class);
    }
}
