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
        $slug = $this->faker->unique()->slug;

        return [
            'name' => $this->faker->word,
            'slug' => $slug,
            'url_path' => $slug,
            'description' => $this->faker->sentence(),
            'locale' => 'en',
            'locale_id' => 1,
        ];
    }
}
