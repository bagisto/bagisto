<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Models\ProductBundleOptionTranslation;

class ProductBundleOptionTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductBundleOptionTranslation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'label'  => $this->faker->words(3, true),
            'locale' => app()->getLocale(),
        ];
    }
}
