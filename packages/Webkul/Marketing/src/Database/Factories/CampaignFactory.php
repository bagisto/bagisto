<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Marketing\Models\Campaign;

class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'name'    => fake()->name(),
            'subject' => fake()->title(),
        ];
    }
}
