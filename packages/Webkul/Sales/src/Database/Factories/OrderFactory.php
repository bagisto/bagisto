<?php

namespace Webkul\Sales\Database\Factories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * @var string[]
     */
    protected $states = [
        'pending',
        'completed',
        'closed',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $lastOrder = DB::table('orders')
                       ->orderBy('id', 'desc')
                       ->select('id')
                       ->first()->id ?? 0;


        $customer = Customer::factory()
                            ->create();

        return [
            'increment_id' => $lastOrder + 1,
            'status' => 'pending',
            'channel_name' => 'Default',
            'is_guest' => 0,
            'customer_id' => $customer->id,
            'customer_email' => $customer->email,
            'customer_first_name' => $customer->first_name,
            'customer_last_name' => $customer->last_name,
            'is_gift' => 0,
            'total_item_count' => 1,
            'total_qty_ordered' => 1,
            'base_currency_code' => 'EUR',
            'channel_currency_code' => 'EUR',
            'order_currency_code' => 'EUR',
            'grand_total' => 0.0000,
            'base_grand_total' => 0.0000,
            'grand_total_invoiced' => 0.0000,
            'base_grand_total_invoiced' => 0.0000,
            'grand_total_refunded' => 0.0000,
            'base_grand_total_refunded' => 0.0000,
            'sub_total' => 0.0000,
            'base_sub_total' => 0.0000,
            'sub_total_invoiced' => 0.0000,
            'base_sub_total_invoiced' => 0.0000,
            'sub_total_refunded' => 0.0000,
            'base_sub_total_refunded' => 0.0000,
            'customer_type' => Customer::class,
            'channel_id' => 1,
            'channel_type' => Channel::class,
            'cart_id' => 0,
            'shipping_method' => 'free_free',
            'shipping_title' => 'Free Shipping',
        ];
    }

    public function pending(): OrderFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    public function completed(): OrderFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
            ];
        });
    }

    public function closed(): OrderFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'closed',
            ];
        });
    }
}
