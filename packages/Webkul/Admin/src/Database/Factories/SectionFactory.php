<?php

namespace Webkul\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Theme\Models\Section as SectionModel;

class SectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SectionModel::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        $lastSection = SectionModel::query()->orderBy('id', 'desc')->limit(1)->first();

        /**
         * A channel draws one footer, so it is not a type to hand out at random; a test
         * that wants one asks for it.
         */
        $types = ['product_carousel', 'category_carousel', 'image_carousel', 'services_content'];

        return [
            'type' => $this->faker->randomElement($types),
            'name' => preg_replace('/[^a-zA-Z ]/', '', $this->faker->name()),
            'sort_order' => ($lastSection ? $lastSection->id : 0) + 1,
            'channel_id' => core()->getCurrentChannel()->id,
            'theme_code' => core()->getCurrentChannel()->theme,
        ];
    }
}
