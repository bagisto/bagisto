<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Support\Facades\Log;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\OrderItem;

class OrderItemRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\Sales\Contracts\OrderItem';
    }

    /**
     * Create.
     *
     * @param  array  $data
     * @return \Webkul\Sales\Contracts\OrderItem
     */
    public function create(array $data)
    {
        if (
            isset($data['product'])
            && $data['product']
        ) {
            $data['product_id'] = $data['product']->id;
            $data['product_type'] = get_class($data['product']);

            unset($data['product']);
        }

        return parent::create($data);
    }

    /**
     * Collect totals.
     *
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return \Webkul\Sales\Contracts\OrderItem
     */
    public function collectTotals($orderItem)
    {
        $qtyShipped = $qtyInvoiced = $qtyRefunded = 0;

        $totalInvoiced = $baseTotalInvoiced = 0;
        $taxInvoiced = $baseTaxInvoiced = 0;

        $totalRefunded = $baseTotalRefunded = 0;
        $taxRefunded = $baseTaxRefunded = 0;

        foreach ($orderItem->invoice_items as $invoiceItem) {
            $qtyInvoiced += $invoiceItem->qty;

            $totalInvoiced += $invoiceItem->total;
            $baseTotalInvoiced += $invoiceItem->base_total;

            $taxInvoiced += $invoiceItem->tax_amount;
            $baseTaxInvoiced += $invoiceItem->base_tax_amount;
        }

        foreach ($orderItem->shipment_items as $shipmentItem) {
            $qtyShipped += $shipmentItem->qty;
        }

        foreach ($orderItem->refund_items as $refundItem) {
            $qtyRefunded += $refundItem->qty;

            $totalRefunded += $refundItem->total;
            $baseTotalRefunded += $refundItem->base_total;

            $taxRefunded += $refundItem->tax_amount;
            $baseTaxRefunded += $refundItem->base_tax_amount;
        }

        $orderItem->qty_shipped = $qtyShipped;
        $orderItem->qty_invoiced = $qtyInvoiced;
        $orderItem->qty_refunded = $qtyRefunded;

        $orderItem->total_invoiced = $totalInvoiced;
        $orderItem->base_total_invoiced = $baseTotalInvoiced;

        $orderItem->tax_amount_invoiced = $taxInvoiced;
        $orderItem->base_tax_amount_invoiced = $baseTaxInvoiced;

        $orderItem->amount_refunded = $totalRefunded;
        $orderItem->base_amount_refunded = $baseTotalRefunded;

        $orderItem->tax_amount_refunded = $taxRefunded;
        $orderItem->base_tax_amount_refunded = $baseTaxRefunded;

        $orderItem->save();

        return $orderItem;
    }

    /**
     * Manage inventory.
     *
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function manageInventory($orderItem)
    {
        $orderItems = [];

        if ($orderItem->getTypeInstance()->isComposite()) {
            foreach ($orderItem->children as $child) {
                $orderItems[] = $child;
            }
        } else {
            $orderItems[] = $orderItem;
        }

        foreach ($orderItems as $item) {
            if (! $item->product) {
                continue;
            }

            if ($item->product->inventories->count() > 0) {
                $orderedInventory = $item->product->ordered_inventories()
                    ->where('channel_id', $orderItem->order->channel_id)
                    ->first();

                if (isset($item->qty_ordered)) {
                    $qty = $item->qty_ordered;
                } else {
                    Log::info('OrderItem has no `qty_ordered`.', ['orderItem' => $item, 'product' => $item->product]);

                    if (isset($item->parent->qty_ordered)) {
                        $qty = $item->parent->qty_ordered;
                    } else {
                        Log::info('OrderItem has no parent with `qty_ordered`', [
                            'orderItem' => $item,
                            'parent'    => $item->parent,
                            'product'   => $item->product,
                        ]);
                        
                        $qty = 1;
                    }
                }

                if ($orderedInventory) {
                    $orderedInventory->update([
                        'qty' => $orderedInventory->qty + $qty,
                    ]);
                } else {
                    $item->product->ordered_inventories()->create([
                        'qty'        => $qty,
                        'product_id' => $item->product_id,
                        'channel_id' => $orderItem->order->channel->id,
                    ]);
                }
            }
        }
    }

    /**
     * Returns qty to product inventory after order cancellation.
     *
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function returnQtyToProductInventory($orderItem)
    {
        $this->updateProductOrderedInventories($orderItem);

        if ($orderItem->getTypeInstance()->isStockable()) {
            $shipmentItems = $orderItem->parent ? $orderItem->parent->shipment_items : $orderItem->shipment_items;

            if ($shipmentItems) {
                foreach ($shipmentItems as $shipmentItem) {
                    if ($orderItem->parent) {
                        $shippedQty = $orderItem->qty_ordered
                            ? ($orderItem->qty_ordered / $orderItem->parent->qty_ordered) * $shipmentItem->qty
                            : $orderItem->parent->qty_ordered;
                    } else {
                        $shippedQty = $shipmentItem->qty;
                    }

                    $inventory = $orderItem->product->inventories()
                        ->where('inventory_source_id', $shipmentItem->shipment->inventory_source_id)
                        ->first();

                    $inventory->update(['qty' => $inventory->qty + $shippedQty]);
                }
            }
        }
    }

    /**
     * Update product ordered quantity.
     *
     * @param  \Webkul\Sales\Contracts\OrderItem  $orderItem
     * @return void
     */
    public function updateProductOrderedInventories($orderItem)
    {
        $orderedInventory = $orderItem->product->ordered_inventories()
            ->where('channel_id', $orderItem->order->channel->id)
            ->first();

        if (! $orderedInventory) {
            return;
        }

        $qty = (
            $orderedInventory->qty +
            (
                isset($orderItem->qty_shipped)
                    ? $orderItem->qty_shipped
                    : $orderItem->parent->qty_shipped
            )
        ) - (
            isset($orderItem->qty_ordered)
                ? $orderItem->qty_ordered
                : $orderItem->parent->qty_ordered
        );

        if ($qty < 0) {
            $qty = 0;
        }

        $orderedInventory->update(['qty' => $qty]);
    }
}
