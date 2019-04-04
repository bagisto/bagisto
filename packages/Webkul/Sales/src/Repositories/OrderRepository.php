<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\Order;
use Webkul\Sales\Repositories\OrderItemRepository;

/**
 * Order Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class OrderRepository extends Repository
{
    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItem;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Sales\Repositories\OrderItemRepository $orderItem
     * @return void
     */
    public function __construct(
        OrderItemRepository $orderItem,
        App $app
    )
    {
        $this->orderItem = $orderItem;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return Order::class;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            Event::fire('checkout.order.save.before', $data);

            if (isset($data['customer']) && $data['customer']) {
                $data['customer_id'] = $data['customer']->id;
                $data['customer_type'] = get_class($data['customer']);
            } else {
                unset($data['customer']);
            }

            if (isset($data['channel']) && $data['channel']) {
                $data['channel_id'] = $data['channel']->id;
                $data['channel_type'] = get_class($data['channel']);
                $data['channel_name'] = $data['channel']->name;
            } else {
                unset($data['channel']);
            }

            $data['status'] = 'pending';

            $order = $this->model->create(array_merge($data, ['increment_id' => $this->generateIncrementId()]));

            $order->payment()->create($data['payment']);

            $order->addresses()->create($data['shipping_address']);

            $order->addresses()->create($data['billing_address']);

            foreach ($data['items'] as $item) {
                $orderItem = $this->orderItem->create(array_merge($item, ['order_id' => $order->id]));

                if (isset($item['child']) && $item['child']) {
                    $orderItem->child = $this->orderItem->create(array_merge($item['child'], ['order_id' => $order->id, 'parent_id' => $orderItem->id]));
                }

                $this->orderItem->manageInventory($orderItem);
            }

            Event::fire('checkout.order.save.after', $order);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return $order;
    }

    /**
     * @param int $orderId
     * @return mixed
     */
    public function cancel($orderId)
    {
        $order = $this->findOrFail($orderId);

        if (! $order->canCancel())
            return false;

        Event::fire('sales.order.cancel.before', $order);

        foreach ($order->items as $item) {
            if ($item->qty_to_cancel) {
                $this->orderItem->returnQtyToProductInventory($item);

                $item->qty_canceled += $item->qty_to_cancel;

                $item->save();
            }
        }

        $this->updateOrderStatus($order);

        Event::fire('sales.order.cancel.after', $order);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function generateIncrementId()
    {
        $lastOrder = $this->model->orderBy('id', 'desc')->limit(1)->first();

        $lastId = $lastOrder ? $lastOrder->id : 0;

        return $lastId + 1;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInCompletedState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyInvoiced = 0;
        $totalQtyShipped = 0;
        $totalQtyRefunded = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items  as $item) {
            $totalQtyOrdered += $item->qty_ordered;
            $totalQtyInvoiced += $item->qty_invoiced;
            $totalQtyShipped += $item->qty_shipped;
            $totalQtyRefunded += $item->qty_refunded;
            $totalQtyCanceled += $item->qty_canceled;
        }

        if ($totalQtyOrdered != ($totalQtyRefunded + $totalQtyCanceled) && 
            $totalQtyOrdered == $totalQtyInvoiced + $totalQtyRefunded + $totalQtyCanceled &&
            $totalQtyOrdered == $totalQtyShipped + $totalQtyRefunded + $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInCanceledState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items as $item) {
            $totalQtyOrdered += $item->qty_ordered;
            $totalQtyCanceled += $item->qty_canceled;
        }

        if ($totalQtyOrdered == $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function isInClosedState($order)
    {
        $totalQtyOrdered = 0;
        $totalQtyRefunded = 0;
        $totalQtyCanceled = 0;

        foreach ($order->items  as $item) {
            $totalQtyOrdered += $item->qty_ordered;
            $totalQtyRefunded += $item->qty_refunded;
            $totalQtyCanceled += $item->qty_canceled;
        }

        if ($totalQtyOrdered == $totalQtyRefunded + $totalQtyCanceled)
            return true;

        return false;
    }

    /**
     * @param mixed $order
     * @return void
     */
    public function updateOrderStatus($order)
    {
        $status = 'processing';

        if ($this->isInCompletedState($order))
            $status = 'completed';

        if ($this->isInCanceledState($order))
            $status = 'canceled';
        else if ($this->isInClosedState($order))
            $status = 'closed';

        $order->status = $status;
        $order->save();
    }

    /**
     * @param mixed $order
     * @return mixed
     */
    public function collectTotals($order)
    {
        $order->sub_total_invoiced = $order->base_sub_total_invoiced = 0;
        $order->shipping_invoiced = $order->base_shipping_invoiced = 0;
        $order->tax_amount_invoiced = $order->base_tax_amount_invoiced = 0;

        foreach ($order->invoices as $invoice) {
            $order->sub_total_invoiced += $invoice->sub_total;
            $order->base_sub_total_invoiced += $invoice->base_sub_total;

            $order->shipping_invoiced += $invoice->shipping_amount;
            $order->base_shipping_invoiced += $invoice->base_shipping_amount;

            $order->tax_amount_invoiced += $invoice->tax_amount;
            $order->base_tax_amount_invoiced += $invoice->base_tax_amount;
        }

        $order->grand_total_invoiced = $order->sub_total_invoiced + $order->shipping_invoiced + $order->tax_amount_invoiced;
        $order->base_grand_total_invoiced = $order->base_sub_total_invoiced + $order->base_shipping_invoiced + $order->base_tax_amount_invoiced;

        $order->save();

        return $order;
    }
}