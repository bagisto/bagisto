<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * States.
     *
     * @var string[]
     */
    protected $states = [
        'pending',
        'completed',
        'closed',
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $lastOrder = DB::table('orders')
            ->orderBy('id', 'desc')
            ->select('id')
            ->first()->id ?? 0;

        return [
            'increment_id'              => $lastOrder + 1,
            'status'                    => 'pending',
            'channel_name'              => 'Default',
            'is_guest'                  => 0,
            'is_gift'                   => 0,
            'total_item_count'          => 1,
            'total_qty_ordered'         => 1,
            'base_currency_code'        => 'USD',
            'channel_currency_code'     => 'USD',
            'order_currency_code'       => 'USD',
            'grand_total'               => $grandTotal = rand(0, 9999),
            'base_grand_total'          => $grandTotal,
            'grand_total_invoiced'      => $grandTotal,
            'base_grand_total_invoiced' => $grandTotal,
            'grand_total_refunded'      => $grandTotal,
            'sub_total'                 => $grandTotal,
            'base_sub_total'            => $grandTotal,
            'sub_total_invoiced'        => $grandTotal,
            'base_sub_total_invoiced'   => $grandTotal,
            'sub_total_refunded'        => $grandTotal,
            'base_sub_total_refunded'   => $grandTotal,
            'base_grand_total_refunded' => 0.0000,
            'customer_type'             => Customer::class,
            'channel_id'                => 1,
            'channel_type'              => Channel::class,
            'cart_id'                   => 0,
            'shipping_method'           => 'free_free',
            'shipping_title'            => 'Free Shipping',
        ];
    }

    /**
     * Pending state.
     */
    public function pending(): OrderFactory
    {
        return $this->state(function () {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Completed state.
     */
    public function completed(): OrderFactory
    {
        return $this->state(function () {
            return [
                'status' => 'completed',
            ];
        });
    }

    /**
     * Closed state.
     */
    public function closed(): OrderFactory
    {
        return $this->state(function () {
            return [
                'status' => 'closed',
            ];
        });
    }
}
