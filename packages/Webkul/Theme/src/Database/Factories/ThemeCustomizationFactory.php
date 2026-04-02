<?php

namespace Webkul\Theme\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Theme\Models\ThemeCustomization;

class ThemeCustomizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThemeCustomization::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $types = ['product_carousel', 'category_carousel', 'image_carousel', 'footer_links', 'services_content'];

        return [
            'type' => $this->faker->randomElement($types),
            'name' => $this->faker->words(3, true),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'channel_id' => core()->getCurrentChannel()->id,
            'theme_code' => core()->getCurrentChannel()->theme,
        ];
    }
}
