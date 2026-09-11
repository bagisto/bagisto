<?php

namespace Webkul\Admin\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Theme\Enums\SectionTypeEnum;
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
     * Define the model's default state, never of a type a channel may hold only one of, which a
     * test that wants one asks for.
     */
    public function definition()
    {
        $lastSection = SectionModel::query()->orderBy('id', 'desc')->limit(1)->first();

        return [
            'type' => $this->faker->randomElement([
                SectionTypeEnum::PRODUCT_CAROUSEL->value,
                SectionTypeEnum::CATEGORY_CAROUSEL->value,
                SectionTypeEnum::IMAGE_CAROUSEL->value,
                SectionTypeEnum::SERVICES_CONTENT->value,
            ]),
            'name' => preg_replace('/[^a-zA-Z ]/', '', $this->faker->name()),
            'sort_order' => ($lastSection ? $lastSection->id : 0) + 1,
            'channel_id' => core()->getDefaultChannel()->id,
            'theme_code' => core()->getDefaultChannel()->theme,
        ];
    }
}
