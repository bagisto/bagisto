<?php

namespace Webkul\Category\Database\Factories;

use Webkul\Category\Models\CategoryTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'        => $this->faker->word,
            'slug'        => $this->faker->unique()->slug,
            'description' => $this->faker->sentence(),
            'locale'      => 'en',
            'locale_id'   => 1,
        ];
    }
}
