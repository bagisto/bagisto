<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");
    $password = $faker->password;
    
    return [
        'first_name'        => $faker->firstName(),
        'last_name'         => $faker->lastName,
        'gender'            => array_random(['male', 'female', 'other']),
        'email'             => $faker->email,
        'status'            => 1,
        'password'          => Hash::make($password),
        'customer_group_id' => 2,
        'is_verified'       => 1,
        'created_at'        => $now,
        'updated_at'        => $now,
        'notes'             => json_encode(['plain_password' => $password]),
    ];
});

$factory->state(Customer::class, 'male', [
    'gender' => 'Male',
]);

$factory->state(Customer::class, 'female', [
    'gender' => 'Female',
]);
