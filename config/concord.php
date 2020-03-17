<?php

return [
    'modules' => [
        /**
         * Example:
         * VendorA\ModuleX\Providers\ModuleServiceProvider::class,
         * VendorB\ModuleY\Providers\ModuleServiceProvider::class
         *
         */

        \Webkul\Attribute\Providers\ModuleServiceProvider::class,
        \Webkul\BookingProduct\Providers\ModuleServiceProvider::class,
        \Webkul\Category\Providers\ModuleServiceProvider::class,
        \Webkul\Checkout\Providers\ModuleServiceProvider::class,
        \Webkul\Core\Providers\ModuleServiceProvider::class,
        \Webkul\Customer\Providers\ModuleServiceProvider::class,
        \Webkul\Inventory\Providers\ModuleServiceProvider::class,
        \Webkul\Product\Providers\ModuleServiceProvider::class,
        \Webkul\Sales\Providers\ModuleServiceProvider::class,
        \Webkul\Tax\Providers\ModuleServiceProvider::class,
        \Webkul\User\Providers\ModuleServiceProvider::class,
        \Webkul\CatalogRule\Providers\ModuleServiceProvider::class,
        \Webkul\CartRule\Providers\ModuleServiceProvider::class,
        \Webkul\CMS\Providers\ModuleServiceProvider::class,
        \Webkul\Velocity\Providers\ModuleServiceProvider::class,
    ]
];