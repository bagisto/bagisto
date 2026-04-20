<?php

use Webkul\Omnibus\PriceProviders\BundleOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\ConfigurableOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\DefaultOmnibusPriceProvider;
use Webkul\Omnibus\PriceProviders\GroupedOmnibusPriceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Feature Flag (fallback)
    |--------------------------------------------------------------------------
    |
    | Last-resort default used by core()->getConfigData() when no per-channel
    | setting is persisted in core_config. Referenced from the admin field's
    | 'default' key in Admin/src/Config/system.php.
    |
    */

    'enabled' => env('OMNIBUS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Snapshots
    |--------------------------------------------------------------------------
    |
    | Tunable parameters that govern the price-history timeline Omnibus
    | maintains per product, channel, and currency.
    |
    |   lookback_days  - days before an active promotion to search when
    |                    computing the lowest price. The EU Omnibus
    |                    Directive mandates 30 days.
    |   retention_days - days to retain snapshot records before garbage
    |                    collection. Must be >= lookback_days to guarantee
    |                    complete coverage; the extra buffer absorbs
    |                    scheduler-timing edge cases.
    |
    */

    'snapshots' => [
        'lookback_days' => 30,
        'retention_days' => 35,
    ],

    /*
    |--------------------------------------------------------------------------
    | Price Providers
    |--------------------------------------------------------------------------
    |
    | Map each product type key (as registered in config/product_types.php)
    | to the OmnibusPriceProvider implementation that describes how Omnibus
    | should record and query its prices. Add an entry here when registering
    | a new product type so its lowest-price calculation and storefront
    | rendering stay accurate.
    |
    |   default - the fallback provider used whenever a product type is
    |             not explicitly listed in the "types" map.
    |   types   - product type key => provider class.
    |
    */

    'providers' => [
        'default' => DefaultOmnibusPriceProvider::class,

        'types' => [
            'simple' => DefaultOmnibusPriceProvider::class,
            'virtual' => DefaultOmnibusPriceProvider::class,
            'downloadable' => DefaultOmnibusPriceProvider::class,
            'booking' => DefaultOmnibusPriceProvider::class,
            'configurable' => ConfigurableOmnibusPriceProvider::class,
            'grouped' => GroupedOmnibusPriceProvider::class,
            'bundle' => BundleOmnibusPriceProvider::class,
        ],
    ],

];
