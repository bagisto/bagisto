<?php

return [
    /**
     * Application service providers.
     */
    App\Providers\AppServiceProvider::class,

    /**
     * Webkul's service providers.
     */
    Webkul\Admin\Providers\AdminServiceProvider::class,
    Webkul\Attribute\Providers\AttributeServiceProvider::class,
    Webkul\CMS\Providers\CMSServiceProvider::class,
    Webkul\CartRule\Providers\CartRuleServiceProvider::class,
    Webkul\CatalogRule\Providers\CatalogRuleServiceProvider::class,
    Webkul\Category\Providers\CategoryServiceProvider::class,
    Webkul\Checkout\Providers\CheckoutServiceProvider::class,
    Webkul\Core\Providers\CoreServiceProvider::class,
    Webkul\Core\Providers\EnvValidatorServiceProvider::class,
    Webkul\Customer\Providers\CustomerServiceProvider::class,
    Webkul\DataGrid\Providers\DataGridServiceProvider::class,
    Webkul\DataTransfer\Providers\DataTransferServiceProvider::class,
    Webkul\DebugBar\Providers\DebugBarServiceProvider::class,
    Webkul\FPC\Providers\FPCServiceProvider::class,
    Webkul\Installer\Providers\InstallerServiceProvider::class,
    Webkul\Inventory\Providers\InventoryServiceProvider::class,
    Webkul\MagicAI\Providers\MagicAIServiceProvider::class,
    Webkul\Marketing\Providers\MarketingServiceProvider::class,
    Webkul\Notification\Providers\NotificationServiceProvider::class,
    Webkul\Payment\Providers\PaymentServiceProvider::class,
    Webkul\Paypal\Providers\PaypalServiceProvider::class,
    Webkul\Product\Providers\ProductServiceProvider::class,
    Webkul\Rule\Providers\RuleServiceProvider::class,
    Webkul\Sales\Providers\SalesServiceProvider::class,
    Webkul\Shipping\Providers\ShippingServiceProvider::class,
    Webkul\Shop\Providers\ShopServiceProvider::class,
    Webkul\Sitemap\Providers\SitemapServiceProvider::class,
    Webkul\SocialLogin\Providers\SocialLoginServiceProvider::class,
    Webkul\SocialShare\Providers\SocialShareServiceProvider::class,
    Webkul\Tax\Providers\TaxServiceProvider::class,
    Webkul\Theme\Providers\ThemeServiceProvider::class,
    Webkul\User\Providers\UserServiceProvider::class,
];
