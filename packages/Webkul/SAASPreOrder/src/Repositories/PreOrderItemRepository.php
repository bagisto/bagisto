<?php

namespace Webkul\SAASPreOrder\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * PreOrderItem Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class PreOrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */

    function model()
    {
        return 'Webkul\SAASPreOrder\Contracts\PreOrderItem';
    }

    /**
     * @param integer $orderId
     * @return boolean
     */
    public function isPreOrderPaymentOrder($orderId)
    {
        return $this->resetScope()->scopeQuery(function ($query) use ($orderId) {
            return $query->leftJoin('order_items', 'pre_order_items.payment_order_item_id', '=', 'order_items.id')
                ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.id', $orderId);
        })->count();
    }

    /**
     * @param integer $orderId
     * @return boolean
     */
    public function havePreOrderItems($orderId)
    {
        return $this->resetScope()->scopeQuery(function ($query) use ($orderId) {
            return $query->where('pre_order_items.order_id', $orderId);
        })->count();
    }

    /**
     * @param OrderItem $orderItem
     * @return boolean
     */
    public function canBeComplete($orderItem)
    {
        $this->resetScope();

        $preOrderItem = $this->findOneByField('order_item_id', $orderItem->id);

        if (! $preOrderItem || $preOrderItem->status == 'completed')
            return false;

        if ($orderItem->type == 'configurable') {
            $isInStock = $orderItem->child->product && $this->getProductTotalQuantity($orderItem->child->product) >= $orderItem->qty_ordered ? true : false;
        } else {
            $isInStock = $orderItem->product && $this->getProductTotalQuantity($orderItem->product) >= $orderItem->qty_ordered ? true : false;
        }

        return $isInStock && $orderItem->qty_invoiced == $orderItem->qty_ordered ? true : false;
    }

    /**
     * @return integer
     */
    public function getProductTotalQuantity($product)
    {
        $total = 0;

        $channelInventorySourceIds = core()->getCurrentChannel()
                ->inventory_sources()
                ->where('status', 1)
                ->pluck('id');

        foreach ($product->inventories as $inventory) {
            if (is_numeric($index = $channelInventorySourceIds->search($inventory->inventory_source_id))) {
                $total += $inventory->qty;
            }
        }

        return $total;
    }
}