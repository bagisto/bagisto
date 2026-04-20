<?php

use Webkul\Omnibus\PriceProviders\ConfigurableOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\DefaultOmnibusPriceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Omnibus Price Provider
    |--------------------------------------------------------------------------
    |
    | The fallback provider used whenever a product type is not explicitly
    | registered in the "types" map below. Override this when all unknown
    | product types should share a common custom implementation.
    |
    */

    'default' => DefaultOmnibusPriceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Product Type Price Providers
    |--------------------------------------------------------------------------
    |
    | Map each product type key (as registered in config/product_types.php)
    | to the provider class that implements OmnibusPriceProvider for it.
    | Add an entry here when registering a new product type so its lowest
    | price calculation and storefront rendering stay accurate.
    |
    */

    'types' => [
        'simple' => DefaultOmnibusPriceProvider::class,
        'virtual' => DefaultOmnibusPriceProvider::class,
        'downloadable' => DefaultOmnibusPriceProvider::class,
        'booking' => DefaultOmnibusPriceProvider::class,
        'grouped' => DefaultOmnibusPriceProvider::class,
        'bundle' => DefaultOmnibusPriceProvider::class,
        'configurable' => ConfigurableOmnibusPriceProvider::class,
    ],

];
