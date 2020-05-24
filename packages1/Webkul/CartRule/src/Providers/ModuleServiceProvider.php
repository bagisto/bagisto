<?php

namespace Webkul\CartRule\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\CartRule\Models\CartRule::class,
        \Webkul\CartRule\Models\CartRuleTranslation::class,
        \Webkul\CartRule\Models\CartRuleCustomer::class,
        \Webkul\CartRule\Models\CartRuleCoupon::class,
        \Webkul\CartRule\Models\CartRuleCouponUsage::class
    ];
}