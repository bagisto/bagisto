<?php

namespace Webkul\Checkout\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Checkout\Models\Cart::class,
        \Webkul\Checkout\Models\CartAddress::class,
        \Webkul\Checkout\Models\CartItem::class,
        \Webkul\Checkout\Models\CartPayment::class,
        \Webkul\Checkout\Models\CartShippingRate::class,
    ];
}
