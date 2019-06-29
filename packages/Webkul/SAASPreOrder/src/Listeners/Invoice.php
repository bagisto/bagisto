<?php

namespace Webkul\SAASPreOrder\Listeners;

use Webkul\SAASPreOrder\Repositories\PreOrderItemRepository;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * Invoice event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Invoice
{
    /**
     * PreOrderItemRepository object
     *
     * @var Object
    */
    protected $preOrderItemRepository;

    /**
     * OrderRepository object
     *
     * @var Object
    */
    protected $orderRepository;

    /**
     * Create a new Order event listener instance.
     *
     * @param  Webkul\PreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @param  Webkul\Sales\Repositories\OrderRepository $orderRepository
     * @return void
     */
    public function __construct(PreOrderItemRepository $preOrderItemRepository, OrderRepository $orderRepository)
    {
        $this->preOrderItemRepository = $preOrderItemRepository;

        $this->orderRepository = $orderRepository;
    }

    /**
     * After sales invoice creation, creater marketplace invoice
     *
     * @param mixed $invoice
     */
    public function afterInvoice($invoice)
    {
        foreach ($invoice->items()->get() as $item) {
            if (isset($item->additional['pre_order_payment'])) {
                $preOrderItem = $this->preOrderItemRepository->findOneByField('order_item_id', $item->additional['order_item_id']);

                $this->preOrderItemRepository->update([
                    'status' => 'completed',
                ], $preOrderItem->id);

                if (! $item->order_item->qty_to_invoice) {
                    $this->orderRepository->update([
                        'status' => 'completed',
                    ], $item->order_item->order_id);
                }
            } else {
                $preOrderItem = $this->preOrderItemRepository->findOneByField('order_item_id', $item->order_item_id);

                if (! $preOrderItem || $preOrderItem->base_remaining_amount)
                    return;

                $this->preOrderItemRepository->update([
                    'status' => 'completed',
                ], $preOrderItem->id);
            }
        }
    }
}
