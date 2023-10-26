<?php

namespace Webkul\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Models\SubscribersList;

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
