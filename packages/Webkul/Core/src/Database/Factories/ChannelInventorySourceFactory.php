<?php

namespace Webkul\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelInventorySourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = 'channel_inventory_sources';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'channel_id' => core()->getCurrentChannel()->id,
            'inventory_source_id' => 1,
        ];
    }
}
