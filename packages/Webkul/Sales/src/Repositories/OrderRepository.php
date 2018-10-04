<?php

namespace Webkul\Sales\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;

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
        return 'Webkul\Sales\Contracts\Order';
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $this->validateOrder();

        DB::beginTransaction();
        
        try {
            Event::fire('checkout.order.save.before', $data);

            $order = null;

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
        
        DB::commit();

        Event::fire('checkout.order.save.after', $order);

        return $order;
    }
}