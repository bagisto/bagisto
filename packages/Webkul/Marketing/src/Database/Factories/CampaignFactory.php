<?php

namespace Webkul\Marketing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Marketing\Models\Campaign;
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
    public function definition()
    {
        return [
            'name'                  => $name = fake()->name(),
            'subject'               => $subject = fake()->title(),
            'marketing_template_id' => Template::factory(),
        ];
    }
}
