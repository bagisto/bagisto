<?php

namespace Webkul\CartRule\Providers;

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CartRule\Models\CartRuleCouponUsage;
use Webkul\CartRule\Models\CartRuleCustomer;
use Webkul\CartRule\Models\CartRuleTranslation;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        CartRule::class,
        CartRuleCoupon::class,
        CartRuleCouponUsage::class,
        CartRuleCustomer::class,
        CartRuleTranslation::class,
    ];
}
