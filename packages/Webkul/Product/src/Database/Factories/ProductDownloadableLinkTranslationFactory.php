<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\ProductDownloadableLinkTranslation;

class ProductDownloadableLinkTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductDownloadableLinkTranslation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'locale' => 'en',
            'title'  => $this->faker->word,
        ];
    }
}
