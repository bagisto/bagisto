<?php

return [

    'convention' => Webkul\Core\CoreConvention::class,

    'modules' => [

        /**
         * Example:
         * VendorA\ModuleX\Providers\ModuleServiceProvider::class,
         * VendorB\ModuleY\Providers\ModuleServiceProvider::class
         *
         */

        \Webkul\Admin\Providers\ModuleServiceProvider::class,
        \Webkul\Attribute\Providers\ModuleServiceProvider::class,
        \Webkul\BookingProduct\Providers\ModuleServiceProvider::class,
        \Webkul\CartRule\Providers\ModuleServiceProvider::class,
        \Webkul\CatalogRule\Providers\ModuleServiceProvider::class,
        \Webkul\Category\Providers\ModuleServiceProvider::class,
        \Webkul\Checkout\Providers\ModuleServiceProvider::class,
        \Webkul\Core\Providers\ModuleServiceProvider::class,
        \Webkul\CMS\Providers\ModuleServiceProvider::class,
        \Webkul\Customer\Providers\ModuleServiceProvider::class,
        \Webkul\Inventory\Providers\ModuleServiceProvider::class,
        \Webkul\Marketing\Providers\ModuleServiceProvider::class,
        \Webkul\Payment\Providers\ModuleServiceProvider::class,
        \Webkul\Paypal\Providers\ModuleServiceProvider::class,
        \Webkul\Product\Providers\ModuleServiceProvider::class,
        \Webkul\Rule\Providers\ModuleServiceProvider::class,
        \Webkul\Sales\Providers\ModuleServiceProvider::class,
        \Webkul\Shipping\Providers\ModuleServiceProvider::class,
        \Webkul\Shop\Providers\ModuleServiceProvider::class,
        \Webkul\SocialLogin\Providers\ModuleServiceProvider::class,
        \Webkul\Tax\Providers\ModuleServiceProvider::class,
        \Webkul\Theme\Providers\ModuleServiceProvider::class,
        \Webkul\Ui\Providers\ModuleServiceProvider::class,
        \Webkul\User\Providers\ModuleServiceProvider::class,
        \Webkul\Velocity\Providers\ModuleServiceProvider::class,

    ],
];
