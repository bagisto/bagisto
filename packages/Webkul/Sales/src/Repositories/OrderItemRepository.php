<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\OrderItem;

/**
 * OrderItem Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return Mixed
     */
    function model()
    {
        return OrderItem::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        if (isset($data['product']) && $data['product']) {
            $data['product_id'] = $data['product']->id;
            $data['product_type'] = get_class($data['product']);

            unset($data['product']);
        }

        return $this->model->create($data);
    }

    /**
     * @param mixed $orderItem
     * @return mixed
     */
    public function collectTotals($orderItem)
    {
        $qtyShipped = $qtyInvoiced = 0;
        $totalInvoiced = $baseTotalInvoiced = 0;
        $taxInvoiced = $baseTaxInvoiced = 0;

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

        $orderItem->qty_shipped = $qtyShipped;
        $orderItem->qty_invoiced = $qtyInvoiced;

        $orderItem->total_invoiced = $totalInvoiced;
        $orderItem->base_total_invoiced = $baseTotalInvoiced;

        $orderItem->tax_amount_invoiced = $taxInvoiced;
        $orderItem->base_tax_amount_invoiced = $baseTaxInvoiced;

        $orderItem->save();

        return $orderItem;
    }

    /**
     * @param mixed $orderItem
     * @return void
     */
    public function manageInventory($orderItem)
    {
        if (! $orderedQuantity = $orderItem->qty_ordered)
            return;

        $product = $orderItem->type == 'configurable' ? $orderItem->child->product : $orderItem->product;

        if (! $product)
            return;


        $orderedInventory = $product->ordered_inventories()
            ->where('channel_id', $orderItem->order->channel->id)
            ->first();

        if ($orderedInventory) {
            $orderedInventory->update([
                    'qty' => $orderedInventory->qty + $orderItem->qty_ordered
                ]);
        } else {
            $product->ordered_inventories()->create([
                    'qty' => $orderItem->qty_ordered,
                    'product_id' => $product->id,
                    'channel_id' => $orderItem->order->channel->id,
                ]);
        }
    }

    /**
     * Returns qty to product inventory after order cancelation
     *
     * @param mixed $orderItem
     * @return void
     */
    public function returnQtyToProductInventory($orderItem)
    {
        if (! $product = $orderItem->product)
            return;

        $orderedInventory = $product->ordered_inventories()
                ->where('channel_id', $orderItem->order->channel->id)
                ->first();

        if (! $orderedInventory)
            return ;

        if (($qty = $orderedInventory->qty - $orderItem->qty_to_cancel) < 0) {
            $qty = 0;
        }

        $orderedInventory->update([
                'qty' => $qty
            ]);
    }
}