<?php

namespace Webkul\Sales\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Sales\Models\OrderAddress::class,
        \Webkul\Sales\Models\Order::class,
        \Webkul\Sales\Models\OrderItem::class,
        \Webkul\Sales\Models\Invoice::class,
        \Webkul\Sales\Models\InvoiceItem::class,
        \Webkul\Sales\Models\Shipment::class,
        \Webkul\Sales\Models\ShipmentItem::class,
    ];
}