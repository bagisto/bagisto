<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Customer\Models\Customer;
use Illuminate\Support\Arr;
use Webkul\Customer\Models\CustomerAddress;

$factory->define(CustomerAddress::class, function (Faker $faker) {
    // use an locale from a country in europe so the vat id can be generated
    $fakerIt = \Faker\Factory::create('it_IT');

    return [
        'customer_id'     => function () {
            return factory(Customer::class)->create()->id;
        },
        'company_name'    => $faker->company,
        'vat_id'          => $fakerIt->vatId(),
        'first_name'      => $faker->firstName,
        'last_name'       => $faker->lastName,
        'address1'        => $faker->streetAddress,
        'country'         => $faker->countryCode,
        'state'           => $faker->state,
        'city'            => $faker->city,
        'postcode'        => $faker->postcode,
        'phone'           => $faker->e164PhoneNumber,
        'default_address' => Arr::random([0, 1]),
        'address_type'    => CustomerAddress::ADDRESS_TYPE,
    ];
});



