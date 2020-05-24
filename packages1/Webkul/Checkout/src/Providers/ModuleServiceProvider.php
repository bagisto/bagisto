<?php

namespace Webkul\Checkout\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Checkout\Models\Cart::class,
        \Webkul\Checkout\Models\CartAddress::class,
        \Webkul\Checkout\Models\CartItem::class,
        \Webkul\Checkout\Models\CartPayment::class,
        \Webkul\Checkout\Models\CartShippingRate::class,
    ];
}