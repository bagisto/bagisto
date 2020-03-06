<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

$factory->define(Order::class, function (Faker $faker) {
    $lastOrder = DB::table('orders')
            ->orderBy('id', 'desc')
            ->select('id')
            ->first()
            ->id ?? 0;


    $customer = factory(Customer::class)->create();

    return [
        'increment_id'              => $lastOrder + 1,
        'status'                    => 'pending',
        'channel_name'              => 'Default',
        'is_guest'                  => 0,
        'customer_id'               => $customer->id,
        'customer_email'            => $customer->email,
        'customer_first_name'       => $customer->first_name,
        'customer_last_name'        => $customer->last_name,
        'is_gift'                   => 0,
        'total_item_count'          => 1,
        'total_qty_ordered'         => 1,
        'base_currency_code'        => 'EUR',
        'channel_currency_code'     => 'EUR',
        'order_currency_code'       => 'EUR',
        'grand_total'               => 0.0000,
        'base_grand_total'          => 0.0000,
        'grand_total_invoiced'      => 0.0000,
        'base_grand_total_invoiced' => 0.0000,
        'grand_total_refunded'      => 0.0000,
        'base_grand_total_refunded' => 0.0000,
        'sub_total'                 => 0.0000,
        'base_sub_total'            => 0.0000,
        'sub_total_invoiced'        => 0.0000,
        'base_sub_total_invoiced'   => 0.0000,
        'sub_total_refunded'        => 0.0000,
        'base_sub_total_refunded'   => 0.0000,
        'customer_type'             => Customer::class,
        'channel_id'                => 1,
        'channel_type'              => Channel::class,
        'cart_id'                   => 0,
        'shipping_method'           => 'free_free',
        'shipping_title'            => 'Free Shipping',
    ];
});

$factory->state(Order::class, 'pending', [
    'status' => 'pending',
]);

$factory->state(Order::class, 'completed', [
    'status' => 'completed',
]);

$factory->state(Order::class, 'closed', [
    'status' => 'closed',
]);
