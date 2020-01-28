<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Core\Models\SubscribersList;

$factory->define(SubscribersList::class, function (Faker $faker) {
    return [
        'email'         => $faker->safeEmail,
        'is_subscribed' => $faker->boolean,
        'channel_id'    => 1,
    ];
});
