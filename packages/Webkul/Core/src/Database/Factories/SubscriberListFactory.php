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
            'email'         => $this->faker->email,
            'channel_id'    => core()->getCurrentChannel()->id,
            'is_subscribed' => 1,
            'token'         => uniqid(),
        ];
    }
}
