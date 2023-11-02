<?php

namespace Webkul\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Category\Models\CategoryTranslation;

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
