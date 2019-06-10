<?php

namespace Webkul\Discount\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Discount\Models\CatalogRule::class,
        \Webkul\Discount\Models\CatalogRuleChannels::class,
        \Webkul\Discount\Models\CatalogRuleCustomerGroups::class,
        \Webkul\Discount\Models\CatalogRuleProducts::class,\Webkul\Discount\Models\CatalogRuleProductsPrice::class,
        \Webkul\Discount\Models\CartRule::class,
        \Webkul\Discount\Models\CartRuleChannels::class,
        \Webkul\Discount\Models\CartRuleCustomerGroups::class,
        \Webkul\Discount\Models\CartRuleCoupons::class,
        \Webkul\Discount\Models\CartRuleLabels::class,
        \Webkul\Discount\Models\CartRuleCouponsUsage::class,
        \Webkul\Discount\Models\CartRuleCustomers::class,
        \Webkul\Discount\Models\CartRuleCart::class,
    ];
}