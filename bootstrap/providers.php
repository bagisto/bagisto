<?php

use App\Providers\AppServiceProvider;
use Webkul\Admin\Providers\AdminServiceProvider;
use Webkul\Attribute\Providers\AttributeServiceProvider;
use Webkul\BookingProduct\Providers\BookingProductServiceProvider;
use Webkul\CartRule\Providers\CartRuleServiceProvider;
use Webkul\CatalogRule\Providers\CatalogRuleServiceProvider;
use Webkul\Category\Providers\CategoryServiceProvider;
use Webkul\Checkout\Providers\CheckoutServiceProvider;
use Webkul\CMS\Providers\CMSServiceProvider;
use Webkul\Core\Providers\CoreServiceProvider;
use Webkul\Core\Providers\EnvValidatorServiceProvider;
use Webkul\Customer\Providers\CustomerServiceProvider;
use Webkul\DataGrid\Providers\DataGridServiceProvider;
use Webkul\DataTransfer\Providers\DataTransferServiceProvider;
use Webkul\DebugBar\Providers\DebugBarServiceProvider;
use Webkul\FPC\Providers\FPCServiceProvider;
use Webkul\GDPR\Providers\GDPRServiceProvider;
use Webkul\Installer\Providers\InstallerServiceProvider;
use Webkul\Inventory\Providers\InventoryServiceProvider;
use Webkul\MagicAI\Providers\MagicAIServiceProvider;
use Webkul\Marketing\Providers\MarketingServiceProvider;
use Webkul\Notification\Providers\NotificationServiceProvider;
use Webkul\Payment\Providers\PaymentServiceProvider;
use Webkul\Paypal\Providers\PaypalServiceProvider;
use Webkul\Product\Providers\ProductServiceProvider;
use Webkul\Rule\Providers\RuleServiceProvider;
use Webkul\Sales\Providers\SalesServiceProvider;
use Webkul\Shipping\Providers\ShippingServiceProvider;
use Webkul\Shop\Providers\ShopServiceProvider;
use Webkul\Sitemap\Providers\SitemapServiceProvider;
use Webkul\SocialLogin\Providers\SocialLoginServiceProvider;
use Webkul\SocialShare\Providers\SocialShareServiceProvider;
use Webkul\Tax\Providers\TaxServiceProvider;
use Webkul\Theme\Providers\ThemeServiceProvider;
use Webkul\User\Providers\UserServiceProvider;

return [
    /**
     * Application service providers.
     */
    AppServiceProvider::class,

    /**
     * Webkul's service providers.
     */
    AdminServiceProvider::class,
    AttributeServiceProvider::class,
    BookingProductServiceProvider::class,
    CMSServiceProvider::class,
    CartRuleServiceProvider::class,
    CatalogRuleServiceProvider::class,
    CategoryServiceProvider::class,
    CheckoutServiceProvider::class,
    CoreServiceProvider::class,
    EnvValidatorServiceProvider::class,
    CustomerServiceProvider::class,
    DataGridServiceProvider::class,
    DataTransferServiceProvider::class,
    DebugBarServiceProvider::class,
    FPCServiceProvider::class,
    GDPRServiceProvider::class,
    InstallerServiceProvider::class,
    InventoryServiceProvider::class,
    MagicAIServiceProvider::class,
    MarketingServiceProvider::class,
    NotificationServiceProvider::class,
    PaymentServiceProvider::class,
    PaypalServiceProvider::class,
    ProductServiceProvider::class,
    RuleServiceProvider::class,
    SalesServiceProvider::class,
    ShippingServiceProvider::class,
    ShopServiceProvider::class,
    SitemapServiceProvider::class,
    SocialLoginServiceProvider::class,
    SocialShareServiceProvider::class,
    TaxServiceProvider::class,
    ThemeServiceProvider::class,
    UserServiceProvider::class,
];
