<?php

namespace Webkul\SAASPreOrder\Listeners;

use Cart as CartFacade;
use Webkul\SAASPreOrder\Repositories\PreOrderItemRepository;
use Webkul\Product\Helpers\Price as PriceHelper;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * PriceHelper object
     *
     * @var Object
    */
    protected $priceHelper;

    /**
     * PreOrderItemRepository object
     *
     * @var Object
    */
    protected $preOrderItemRepository;

    /**
     * Create a new Order event listener instance.
     *
     * @param  Webkul\Product\Helpers\Price                        $priceHelper
     * @param  Webkul\SAASPreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(
        PriceHelper $priceHelper,
        PreOrderItemRepository $preOrderItemRepository
    )
    {
        $this->priceHelper = $priceHelper;

        $this->preOrderItemRepository = $preOrderItemRepository;
    }

    /**
     * After sales order creation, add entry to pre_order_items order table
     *
     * @param mixed $order
     */
    public function afterPlaceOrder($order)
    {
        foreach ($order->items()->get() as $item) {
            if (isset($item->additional['pre_order_payment'])) {
                $preOrderItem = $this->preOrderItemRepository->findOneByField('order_item_id', $item->additional['order_item_id']);

                $this->preOrderItemRepository->update([
                    'status' => 'processing',
                    'payment_order_item_id' => $item->id
                ], $preOrderItem->id);
            } else {
                if ($item->product->totalQuantity() > 0 || ! $item->product->allow_preorder)
                    continue;

                if (core()->getConfigData('preorder.settings.general.preorder_type') == 'partial') {
                    $preOrderType = 'partial';

                    if (is_null(core()->getConfigData('preorder.settings.general.percent'))) {
                        $preOrderPercentage =  0;
                    } else {
                        $preOrderPercentage = core()->getConfigData('preorder.settings.general.percent');
                    }
                } else {
                    $preOrderType = 'complete';

                    $preOrderPercentage = 100;
                }

                $productPrice = $item->type == 'configurable'
                        ? $this->priceHelper->getMinimalPrice($item->child->product)
                        : $this->priceHelper->getMinimalPrice($item->product);

                $this->preOrderItemRepository->create([
                        'preorder_type' => $preOrderType,
                        'preorder_percent' => $preOrderPercentage,
                        'status' => 'pending',
                        'paid_amount' => $item->total,
                        'base_paid_amount' => $item->base_total,
                        'base_remaining_amount' => ($productPrice * $item->qty_ordered) - $item->base_total,
                        'order_id' => $order->id,
                        'order_item_id' => $item->id,
                        'token' => str_random(32)
                    ]);
            }
        }
    }
}