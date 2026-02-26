<?php

namespace Webkul\CartRule\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\CartRule\Models\CartRule::class,
        \Webkul\CartRule\Models\CartRuleCoupon::class,
        \Webkul\CartRule\Models\CartRuleCouponUsage::class,
        \Webkul\CartRule\Models\CartRuleCustomer::class,
        \Webkul\CartRule\Models\CartRuleTranslation::class,
    ];
}
