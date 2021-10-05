<?php

namespace Webkul\Core\Database\Factories;

use Webkul\Core\Models\SubscribersList;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SubscribersList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'email'         => $this->faker->safeEmail,
            'is_subscribed' => $this->faker->boolean,
            'channel_id'    => 1,
        ];
    }
}

