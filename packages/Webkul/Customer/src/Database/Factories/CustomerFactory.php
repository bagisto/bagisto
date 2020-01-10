<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");
    $gender = array_rand(['male', 'female', 'other']);
    $password = $faker->password;
    return [
        'first_name'        => $faker->firstName($gender),
        'last_name'         => $faker->lastName,
        'gender'            => ucfirst($gender),
        'email'             => $faker->email,
        'status'            => 1,
        'password'          => Hash::make($password),
        'customer_group_id' => 2,
        'is_verified'       => 1,
        'created_at'        => $now,
        'updated_at'        => $now,
        'notes'             => json_encode(array('plain_password' => $password)),
    ];
});

$factory->state(Customer::class, 'male', [
    'gender' => 'Male',
]);
$factory->state(Customer::class, 'female', [
    'gender' => 'Female',
]);


