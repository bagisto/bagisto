<?php

return [

    /**
     * The path of the convention file.
     */
    'convention' => Webkul\Core\CoreConvention::class,

    /**
     * Example:
     *
     * VendorA\ModuleX\Providers\ModuleServiceProvider::class,
     * VendorB\ModuleY\Providers\ModuleServiceProvider::class,
     */
    'modules' => [
        \Webkul\Admin\Providers\ModuleServiceProvider::class,
        \Webkul\Attribute\Providers\ModuleServiceProvider::class,
        \Webkul\CMS\Providers\ModuleServiceProvider::class,
        \Webkul\CartRule\Providers\ModuleServiceProvider::class,
        \Webkul\CatalogRule\Providers\ModuleServiceProvider::class,
        \Webkul\Category\Providers\ModuleServiceProvider::class,
        \Webkul\Checkout\Providers\ModuleServiceProvider::class,
        \Webkul\Core\Providers\ModuleServiceProvider::class,
        \Webkul\Customer\Providers\ModuleServiceProvider::class,
        \Webkul\DataGrid\Providers\ModuleServiceProvider::class,
        \Webkul\DataTransfer\Providers\ModuleServiceProvider::class,
        \Webkul\Inventory\Providers\ModuleServiceProvider::class,
        \Webkul\Marketing\Providers\ModuleServiceProvider::class,
        \Webkul\Notification\Providers\ModuleServiceProvider::class,
        \Webkul\Payment\Providers\ModuleServiceProvider::class,
        \Webkul\Paypal\Providers\ModuleServiceProvider::class,
        \Webkul\Product\Providers\ModuleServiceProvider::class,
        \Webkul\Rule\Providers\ModuleServiceProvider::class,
        \Webkul\Sales\Providers\ModuleServiceProvider::class,
        \Webkul\Shipping\Providers\ModuleServiceProvider::class,
        \Webkul\Shop\Providers\ModuleServiceProvider::class,
        \Webkul\Sitemap\Providers\ModuleServiceProvider::class,
        \Webkul\SocialLogin\Providers\ModuleServiceProvider::class,
        \Webkul\Tax\Providers\ModuleServiceProvider::class,
        \Webkul\Theme\Providers\ModuleServiceProvider::class,
        \Webkul\User\Providers\ModuleServiceProvider::class,
    ],

];
