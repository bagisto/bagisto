<?php

namespace Webkul\SAASPreOrder\Listeners;

use Webkul\SAASPreOrder\Repositories\PreOrderItemRepository;

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
     * Create a new Order event listener instance.
     *
     * @param  Webkul\SAASPreOrder\Repositories\PreOrderItemRepository $preOrderItemRepository
     * @return void
     */
    public function __construct(PreOrderItemRepository $preOrderItemRepository)
    {
        $this->preOrderItemRepository = $preOrderItemRepository;
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