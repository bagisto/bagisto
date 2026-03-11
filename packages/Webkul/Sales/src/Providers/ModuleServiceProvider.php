<?php

namespace Webkul\Sales\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Sales\Models\DownloadableLinkPurchased;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\InvoiceItem;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Sales\Models\OrderComment;
use Webkul\Sales\Models\OrderItem;
use Webkul\Sales\Models\OrderPayment;
use Webkul\Sales\Models\OrderTransaction;
use Webkul\Sales\Models\Refund;
use Webkul\Sales\Models\RefundItem;
use Webkul\Sales\Models\Shipment;
use Webkul\Sales\Models\ShipmentItem;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        DownloadableLinkPurchased::class,
        Invoice::class,
        InvoiceItem::class,
        Order::class,
        OrderAddress::class,
        OrderComment::class,
        OrderItem::class,
        OrderPayment::class,
        OrderTransaction::class,
        Refund::class,
        RefundItem::class,
        Shipment::class,
        ShipmentItem::class,
    ];
}
