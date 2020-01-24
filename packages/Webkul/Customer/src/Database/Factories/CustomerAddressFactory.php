<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

$factory->define(CustomerAddress::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");

    // use an locale from a country in europe so the vat id can be generated
    $fakerIt = \Faker\Factory::create('it_IT');

    return [
        'customer_id'     => function () {
            return factory(Customer::class)->create()->id;
        },
        'company_name'    => $faker->company,
        'name'            => $faker->name,
        'vat_id'          => $fakerIt->vat,
        'address1'        => $faker->streetAddress,
        'country'         => $faker->countryCode,
        'state'           => $faker->state,
        'city'            => $faker->city,
        'postcode'        => $faker->postcode,
        'phone'           => $faker->e164PhoneNumber,
        'default_address' => array_random([0, 1]),
        'created_at'      => $now,
        'updated_at'      => $now,
    ];
});



