<?php

namespace Webkul\Sales\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        \Webkul\Sales\Models\DownloadableLinkPurchased::class,
        \Webkul\Sales\Models\Invoice::class,
        \Webkul\Sales\Models\InvoiceItem::class,
        \Webkul\Sales\Models\Order::class,
        \Webkul\Sales\Models\OrderAddress::class,
        \Webkul\Sales\Models\OrderComment::class,
        \Webkul\Sales\Models\OrderItem::class,
        \Webkul\Sales\Models\OrderPayment::class,
        \Webkul\Sales\Models\OrderTransaction::class,
        \Webkul\Sales\Models\Refund::class,
        \Webkul\Sales\Models\RefundItem::class,
        \Webkul\Sales\Models\Shipment::class,
        \Webkul\Sales\Models\ShipmentItem::class,
    ];
}
