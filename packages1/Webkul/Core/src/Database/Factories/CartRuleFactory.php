<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\CartRule\Models\CartRule;

$factory->define(CartRule::class, function (Faker $faker) {
    return [
        'name'                      => $faker->uuid,
        'description'               => $faker->sentence(),
        'starts_from'               => null,
        'ends_till'                 => null,
        'coupon_type'               => '1',
        'use_auto_generation'       => '0',
        'usage_per_customer'        => '100',
        'uses_per_coupon'           => '100',
        'times_used'                => '0',
        'condition_type'            => '2',
        'end_other_rules'           => '0',
        'uses_attribute_conditions' => '0',
        'discount_quantity'         => '0',
        'discount_step'             => '0',
        'apply_to_shipping'         => '0',
        'free_shipping'             => '0',
        'sort_order'                => '0',
        'status'                    => '1',
        'conditions'                => null,
    ];
});