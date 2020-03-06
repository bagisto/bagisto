<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderItem as OrderItemContract;
use Webkul\Product\Models\Product;

class OrderItem extends Model implements OrderItemContract
{
    protected $guarded = [
        'id',
        'child',
        'children',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    protected $typeInstance;

    /**
     * Retrieve type instance
     *
     * @return AbstractType
     */
    public function getTypeInstance()
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

        if ($this->product) {
            $this->typeInstance->setProduct($this);
        }

        return $this->typeInstance;
    }

    /**
     * @return bool
     */
    public function isStockable()
    {
        return $this->getTypeInstance()->isStockable();
    }

    /**
     * Checks if new shipment is allow or not
     */
    public function canShip()
    {
        if (! $this->isStockable()) {
            return false;
        }

        if ($this->qty_to_ship > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get remaining qty for shipping.
     */
    public function getQtyToShipAttribute()
    {
        if (! $this->isStockable()) {
            return 0;
        }

        return $this->qty_ordered - $this->qty_shipped - $this->qty_refunded - $this->qty_canceled;
    }

    /**
     * Checks if new invoice is allow or not
     */
    public function canInvoice()
    {
        if ($this->qty_to_invoice > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get remaining qty for invoice.
     */
    public function getQtyToInvoiceAttribute()
    {
        return $this->qty_ordered - $this->qty_invoiced - $this->qty_canceled;
    }

    /**
     * Checks if new cancel is allow or not
     */
    public function canCancel()
    {
        if ($this->qty_to_cancel > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get remaining qty for cancel.
     */
    public function getQtyToCancelAttribute()
    {
        return $this->qty_ordered - $this->qty_canceled - $this->qty_invoiced;
    }

    /**
     * Get remaining qty for refund.
     */
    public function getQtyToRefundAttribute()
    {
        return $this->qty_invoiced - $this->qty_refunded;
    }

    /**
     * Get the order record associated with the order item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order record associated with the order item.
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the order item.
     */
    public function child()
    {
        return $this->hasOne(OrderItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get the parent item record associated with the order item.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the invoice items record associated with the order item.
     */
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItemProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the order item.
     */
    public function shipment_items()
    {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }

    /**
     * Get the refund items record associated with the order item.
     */
    public function refund_items()
    {
        return $this->hasMany(RefundItemProxy::modelClass());
    }

    /**
     * Returns configurable option html
     */
    public function downloadable_link_purchased()
    {
        return $this->hasMany(DownloadableLinkPurchasedProxy::modelClass());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['qty_to_ship'] = $this->qty_to_ship;

        $array['qty_to_invoice'] = $this->qty_to_invoice;

        $array['qty_to_cancel'] = $this->qty_to_cancel;

        $array['qty_to_refund'] = $this->qty_to_refund;

        return $array;
    }
}