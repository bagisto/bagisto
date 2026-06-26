<?php

namespace Webkul\Marketplace\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Marketplace\Models\MarketplaceOrder;
use Webkul\Marketplace\Models\Payout;
use Webkul\Marketplace\Models\Seller;
use Webkul\Marketplace\Models\SellerProduct;
use Webkul\Marketplace\Models\Subscription;
use Webkul\Marketplace\Models\SubscriptionPlan;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        Seller::class,
        SellerProduct::class,
        MarketplaceOrder::class,
        SubscriptionPlan::class,
        Subscription::class,
        Payout::class,
    ];
}
