<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Marketing\Models\Campaign;
use Webkul\Marketing\Models\Event;
use Webkul\Marketing\Models\Template;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'subject' => $this->faker->sentence(),
            'status' => true,
            'marketing_template_id' => Template::factory(),
            'marketing_event_id' => Event::factory(),
            'channel_id' => core()->getCurrentChannel()->id,
            'customer_group_id' => 1,
        ];
    }
}
