<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Attribute\Models\AttributeFamily;

$factory->define(AttributeFamily::class, function (Faker $faker) {
    return [
        'name'            => $faker->word(),
        'code'            => $faker->word(),
        'is_user_defined' => rand(0, 1),
        'status'          => 0,
    ];
});
