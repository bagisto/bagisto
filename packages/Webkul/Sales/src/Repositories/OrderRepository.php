<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Repositories\OrderItemRepository;
use Webkul\Sales\Repositories\OrderItemInventoryRepository;

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
     * OrderItemInventoryRepository object
     *
     * @var Object
     */
    protected $orderItemInventory;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Sales\Repositories\OrderItemRepository          $orderItem
     * @param  Webkul\Sales\Repositories\OrderItemInventoryRepository $orderItemInventory
     * @return void
     */
    public function __construct(
        OrderItemRepository $orderItem,
        OrderItemInventoryRepository $orderItemInventory,
        App $app
    )
    {
        $this->orderItem = $orderItem;

        $this->orderItemInventory = $orderItemInventory;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Sales\Contracts\Order';
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

            if(isset($data['customer']) && $data['customer']) {
                $data['customer_id'] = $data['customer']->id;
                $data['customer_type'] = get_class($data['customer']);
            } else {
                unset($data['customer']);
            }

            if(isset($data['channel']) && $data['channel']) {
                $data['channel_id'] = $data['channel']->id;
                $data['channel_type'] = get_class($data['channel']);
                $data['channel_name'] = $data['channel']->name;
            } else {
                unset($data['channel']);
            }

            $data['status'] = core()->getConfigData('paymentmethods.' . $data['payment']['method'] . '.status') ?? 'pending';

            $order = $this->model->create(array_merge($data, ['increment_id' => $this->generateIncrementId()]));

            $order->payment()->create($data['payment']);

            $order->addresses()->create($data['shipping_address']);
            
            $order->addresses()->create($data['billing_address']);

            foreach($data['items'] as $item) {
                $orderItem = $this->orderItem->create(array_merge($item, ['order_id' => $order->id]));

                if(isset($item['child']) && $item['child']) {
                    $orderItem->child = $this->orderItem->create(array_merge($item['child'], ['order_id' => $order->id, 'parent_id' => $orderItem->id]));
                }

                $this->orderItemInventory->create(['orderItem' => $orderItem]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        
        DB::commit();

        Event::fire('checkout.order.save.after', $order);

        return $order;
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
     * @param int $orderId
     * @return void
     */
    public function updateOrderStatus(int $orderId)
    {
        $order = $this->find($orderId);
    }

    /**
     * @param mixed $order
     * @return mixed
     */
    public function collectTotals($order)
    {
        $subTotalInvoiced = $baseSubTotalInvoiced = 0;
        $shippingInvoiced = $baseShippingInvoiced = 0;
        $taxInvoiced = $baseTaxInvoiced = 0;

        foreach($order->invoices as $invoice) {
            $subTotalInvoiced += $invoice->sub_total;
            $baseSubTotalInvoiced += $invoice->base_sub_total;

            $shippingInvoiced += $invoice->shipping_amount;
            $baseShippingInvoiced += $invoice->base_shipping_amount;

            $taxInvoiced += $invoice->tax_amount;
            $baseTaxInvoiced += $invoice->base_tax_amount;
        }

        $order->sub_total_invoiced = $subTotalInvoiced;
        $order->base_sub_total_invoiced = $baseSubTotalInvoiced;

        $order->shipping_invoiced = $shippingInvoiced;
        $order->base_shipping_invoiced = $baseShippingInvoiced;

        $order->tax_amount_invoiced = $taxInvoiced;
        $order->base_tax_amount_invoiced = $baseTaxInvoiced;

        $order->grand_total_invoiced = $subTotalInvoiced + $shippingInvoiced + $taxInvoiced;
        $order->base_grand_total_invoiced = $baseSubTotalInvoiced + $shippingInvoiced + $baseTaxInvoiced;

        $order->save();

        return $order;
    }
}