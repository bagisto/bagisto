<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

use Webkul\Customer\Models\Customer;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;

$factory->define(Cart::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");

    $lastOrder = DB::table('orders')
        ->orderBy('id', 'desc')
        ->select('id')
        ->first();

    $customer = factory(Customer::class)->create();
    $cartAddress = factory(CartAddress::class)->create();

    return [
        'is_guest'              => 0,
        'is_active'             => 1,
        'customer_id'           => $customer->id,
        'customer_email'        => $customer->email,
        'customer_first_name'   => $customer->first_name,
        'customer_last_name'    => $customer->last_name,
        'is_gift'               => 0,
        'base_currency_code'    => 'EUR',
        'channel_currency_code' => 'EUR',
        'grand_total'           => 0.0000,
        'base_grand_total'      => 0.0000,
        'sub_total'             => 0.0000,
        'base_sub_total'        => 0.0000,
        'channel_id'            => 1,
        'created_at'            => $now,
        'updated_at'            => $now,
    ];
});
