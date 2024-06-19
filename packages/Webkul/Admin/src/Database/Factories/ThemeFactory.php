<?php

namespace Webkul\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Theme\Models\ThemeCustomization as ThemeCustomizationModel;

class ThemeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ThemeCustomizationModel::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        $lastTheme = ThemeCustomizationModel::query()->orderBy('id', 'desc')->limit(1)->first();

        $types = ['product_carousel', 'category_carousel', 'image_carousel', 'footer_links', 'services_content'];

        return [
            'type'       => $this->faker->randomElement($types),
            'name'       => preg_replace('/[^a-zA-Z ]/', '', $this->faker->name()),
            'sort_order' => ($lastTheme ? $lastTheme->id : 0) + 1,
            'channel_id' => core()->getCurrentChannel()->id,
            'theme_code' => core()->getCurrentChannel()->theme,
        ];
    }
}
