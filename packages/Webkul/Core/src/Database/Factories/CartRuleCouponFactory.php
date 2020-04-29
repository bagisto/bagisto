<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;

$factory->define(CartRuleCoupon::class, function (Faker $faker) {
    return [
        'code'               => $faker->uuid(),
        'usage_limit'        => 100,
        'usage_per_customer' => 100,
        'type'               => 0,
        'is_primary'         => 1,
        'cart_rule_id'       => static function () {
            return factory(CartRule::class)->create()->id;
        },
    ];
});
