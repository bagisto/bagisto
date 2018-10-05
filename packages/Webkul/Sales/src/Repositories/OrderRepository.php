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

            die;
            Event::fire('checkout.order.save.before', $data);

            if(isset($data['customer']) && $data['customer'] instanceof Model) {
                $data['customer_id'] = $data['customer']->id;
                $data['customer_type'] = get_class($data['customer']);
            }
            
            $order = $this->model->create(array_merge($data, ['increment_id' => $this->generateIncrementId()]));

            $order->payment()->create($data['payment']);

            $order->addresses()->create($data['shipping_address']);
            
            $order->addresses()->create($data['billing_address']);

            foreach($data['items'] as $item) {
                $orderItem = $this->orderItem->create(array_merge($item, ['order_id' => $order->id]));

                if(isset($item['child']) && $item['child']) {
                    $orderItem = $this->orderItem->create(array_merge($item['child'], ['order_id' => $order->id, 'parent_id' => $orderItem->id]));
                }
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
        $lastOrder = $this->orderBy('id', 'desc')->limit(1)->first();

        $lastId = $lastOrder ? $lastOrder->id : 0;

        return 000000000 + $last + 1;
    }
}