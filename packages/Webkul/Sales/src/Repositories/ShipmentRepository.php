<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Repositories\OrderRepository as Order;
use Webkul\Sales\Repositories\OrderItemRepository as OrderItem;
use Webkul\Sales\Repositories\ShipmentItemRepository as ShipmentItem;

/**
 * Shipment Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */

class ShipmentRepository extends Repository
{
    /**
     * OrderRepository object
     *
     * @var Object
     */
    protected $order;

    /**
     * OrderItemRepository object
     *
     * @var Object
     */
    protected $orderItem;

    /**
     * ShipmentItemRepository object
     *
     * @var Object
     */
    protected $shipmentItem;

    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Sales\Repositories\OrderRepository        $order
     * @param  Webkul\Sales\Repositories\OrderItemRepository    $orderItem
     * @param  Webkul\Sales\Repositories\ShipmentItemRepository $orderItem
     * @return void
     */
    public function __construct(
        Order $order,
        OrderItem $orderItem,
        ShipmentItem $shipmentItem,
        App $app
    )
    {
        $this->order = $order;

        $this->orderItem = $orderItem;

        $this->shipmentItem = $shipmentItem;

        parent::__construct($app);
    }
    
    /**
     * Specify Model class name
     *
     * @return Mixed
     */

    function model()
    {
        return 'Webkul\Sales\Contracts\Shipment';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        DB::beginTransaction();
        
        try {
            Event::fire('sales.shipment.save.before', $data);

            $order = $this->order->find($data['order_id']);

            $totalQty = array_sum($data['shipment']['items']);

            $shipment = $this->model->create([
                    'order_id' => $order->id,
                    'total_qty' => $totalQty,
                    'carrier_title' => $data['shipment']['carrier_title'],
                    'track_number' => $data['shipment']['track_number'],
                    'customer_id' => $order->customer_id,
                    'customer_type' => $order->customer_type,
                    'order_address_id' => $order->shipping_address->id,
                ]);

            foreach ($data['shipment']['items'] as $itemId => $qty) {
                if(!$qty) continue;

                $orderItem = $this->orderItem->find($itemId);

                if($qty > $orderItem->qty_to_ship)
                    $qty = $orderItem->qty_to_ship;

                $shipmentItem = $this->shipmentItem->create([
                        'shipment_id' => $shipment->id,
                        'order_item_id' => $orderItem->id,
                        'name' => $orderItem->name,
                        'sku' => ($orderItem->type == 'configurable' ? $orderItem->child->sku : $orderItem->sku),
                        'qty' => $qty,
                        'weight' => $orderItem->weight * $qty,
                        'price' => $orderItem->price,
                        'base_price' => $orderItem->base_price,
                        'total' => $orderItem->price * $qty,
                        'base_total' => $orderItem->base_price * $qty,
                        'product_id' => $orderItem->product_id,
                        'product_type' => $orderItem->product_type,
                        'additional' => $orderItem->additional,
                    ]);

                $this->orderItem->update(['qty_shipped' => $orderItem->qty_shipped + $qty], $orderItem->id);
            }

            $this->order->updateOrderStatus($order);

            Event::fire('sales.shipment.save.after', $shipment);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        
        DB::commit();

        return $shipment;
    }
}