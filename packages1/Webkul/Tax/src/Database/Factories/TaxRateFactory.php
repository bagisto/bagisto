<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Tax\Models\TaxRate;

$factory->define(TaxRate::class, function (Faker $faker) {
    return [
        'identifier' => $faker->uuid,
        'is_zip'     => 0,
        'zip_code'   => '*',
        'zip_from'   => null,
        'zip_to'     => null,
        'state'      => '',
        'country'    => $faker->countryCode,
        'tax_rate'   => $faker->randomFloat(2, 3, 25),
    ];
});
