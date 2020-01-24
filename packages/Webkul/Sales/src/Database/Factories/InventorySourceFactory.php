<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Inventory\Models\InventorySource;

$factory->define(InventorySource::class, function (Faker $faker) {
    $code = $faker->unique()->word;
    return [
        'code'           => $faker->unique()->word,
        'name'           => $code,
        'description'    => $faker->sentence,
        'contact_name'   => $faker->name,
        'contact_email'  => $faker->safeEmail,
        'contact_number' => $faker->phoneNumber,
        'country'        => $faker->countryCode,
        'state'          => $faker->state,
        'city'           => $faker->city,
        'street'         => $faker->streetAddress,
        'postcode'       => $faker->postcode,
        'priority'       => 0,
        'status'         => 1,
    ];
});
